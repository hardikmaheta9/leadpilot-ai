<?php

namespace App\Services\Documents;

use App\Models\CompanyAiProposal;
use DOMDocument;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use RuntimeException;
use Symfony\Component\Process\Process;
use Throwable;
use ZipArchive;

class ProposalDocxService
{
    public function __construct(
        private ProposalPdfService $pdfService
    ) {
    }

    /**
     * Generate a Word file that visually matches the PDF page-for-page.
     *
     * Important:
     * Each PDF page is placed in Word as a full-page image. This preserves
     * the exact proposal layout, colours, spacing and page count, but the
     * proposal body is not editable as normal Word text.
     */
    public function generate(
        CompanyAiProposal $proposal,
        bool $force = true
    ): string {
        $proposal->loadMissing('company');

        $directory = 'proposals/'
            . $proposal->company_uuid
            . '/version-'
            . $proposal->version;

        Storage::disk('public')->makeDirectory($directory);

        $fileName = Str::slug(
            $proposal->proposal_title
            . '-v'
            . $proposal->version
        ) . '.docx';

        $relativePath = $directory . '/' . $fileName;
        $absolutePath = Storage::disk('public')->path($relativePath);

        /*
         * Always regenerate so an older two-page DOCX is never reused.
         */
        if (is_file($absolutePath)) {
            @unlink($absolutePath);
        }

        $pdfAbsolutePath = $this->pdfService->absolutePath($proposal);

        if (!is_file($pdfAbsolutePath)) {
            throw new RuntimeException(
                'The proposal PDF could not be found.'
            );
        }

        $temporaryDirectory = storage_path(
            'app/proposal-docx-temp/'
            . $proposal->company_uuid
            . '/version-'
            . $proposal->version
            . '-'
            . Str::random(10)
        );

        File::ensureDirectoryExists($temporaryDirectory);

        try {
            $pageImages = $this->renderPdfPages(
                $pdfAbsolutePath,
                $temporaryDirectory
            );

            if ($pageImages === []) {
                throw new RuntimeException(
                    'No PDF pages were rendered for the DOCX export.'
                );
            }

            $this->buildImageBasedDocx(
                $proposal,
                $pageImages,
                $absolutePath
            );

            if (!$this->isValidDocx($absolutePath)) {
                @unlink($absolutePath);

                throw new RuntimeException(
                    'The generated DOCX failed validation.'
                );
            }

            $proposal->forceFill([
                'docx_path' => $relativePath,
            ])->save();

            return $relativePath;
        } finally {
            File::deleteDirectory($temporaryDirectory);
        }
    }

    /**
     * Convert every PDF page to a PNG image.
     *
     * The service first uses the PHP Imagick extension when available.
     * Otherwise it uses Poppler's pdftoppm executable.
     */
    private function renderPdfPages(
        string $pdfPath,
        string $outputDirectory
    ): array {
        if (extension_loaded('imagick')) {
            return $this->renderWithImagick(
                $pdfPath,
                $outputDirectory
            );
        }

        return $this->renderWithPdftoppm(
            $pdfPath,
            $outputDirectory
        );
    }

    private function renderWithImagick(
        string $pdfPath,
        string $outputDirectory
    ): array {
        try {
            $document = new \Imagick();

            /*
             * 150 DPI gives a clean A4 page without making the DOCX too large.
             */
            $document->setResolution(150, 150);
            $document->readImage($pdfPath);

            $files = [];
            $pageNumber = 1;

            foreach ($document as $page) {
                $page->setImageBackgroundColor('white');
                $page->setImageAlphaChannel(
                    \Imagick::ALPHACHANNEL_REMOVE
                );
                $page->mergeImageLayers(
                    \Imagick::LAYERMETHOD_FLATTEN
                );
                $page->setImageFormat('png');
                $page->setImageCompressionQuality(92);

                $path = $outputDirectory
                    . DIRECTORY_SEPARATOR
                    . sprintf('page-%03d.png', $pageNumber);

                $page->writeImage($path);

                if (is_file($path) && filesize($path) > 0) {
                    $files[] = $path;
                }

                $pageNumber++;
            }

            $document->clear();
            $document->destroy();

            return $files;
        } catch (Throwable $exception) {
            throw new RuntimeException(
                'Imagick could not convert the proposal PDF: '
                . $exception->getMessage(),
                previous: $exception
            );
        }
    }

    private function renderWithPdftoppm(
        string $pdfPath,
        string $outputDirectory
    ): array {
        $binary = $this->resolvePdftoppmBinary();

        $prefix = $outputDirectory
            . DIRECTORY_SEPARATOR
            . 'page';

        $process = new Process([
            $binary,
            '-png',
            '-r',
            '150',
            $pdfPath,
            $prefix,
        ]);

        $process->setTimeout(180);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new RuntimeException(
                "PDF-to-image conversion failed.\n"
                . trim($process->getErrorOutput())
                . "\n\nInstall Poppler and set PDFTOPPM_BINARY in .env, "
                . "or enable the PHP Imagick extension."
            );
        }

        $files = glob(
            $outputDirectory
            . DIRECTORY_SEPARATOR
            . 'page-*.png'
        ) ?: [];

        natsort($files);

        return array_values(
            array_filter(
                $files,
                fn (string $path): bool =>
                    is_file($path) && filesize($path) > 0
            )
        );
    }

    private function resolvePdftoppmBinary(): string
    {
        $configured = trim(
            (string) env('PDFTOPPM_BINARY', '')
        );

        if ($configured !== '' && is_file($configured)) {
            return $configured;
        }

        $command = PHP_OS_FAMILY === 'Windows'
            ? ['where', 'pdftoppm']
            : ['which', 'pdftoppm'];

        $process = new Process($command);
        $process->run();

        if ($process->isSuccessful()) {
            $lines = preg_split(
                '/\r\n|\r|\n/',
                trim($process->getOutput())
            ) ?: [];

            $candidate = trim((string) ($lines[0] ?? ''));

            if ($candidate !== '') {
                return $candidate;
            }
        }

        throw new RuntimeException(
            'Neither Imagick nor Poppler pdftoppm is available. '
            . 'Enable the PHP Imagick extension or install Poppler, then set '
            . 'PDFTOPPM_BINARY to the full path of pdftoppm.exe in your .env file.'
        );
    }

    /**
     * Build one Word page for each rendered PDF page.
     */
    private function buildImageBasedDocx(
        CompanyAiProposal $proposal,
        array $pageImages,
        string $outputPath
    ): void {
        $word = new PhpWord();

        $word->getDocInfo()
            ->setCreator('WebApp Infoway')
            ->setCompany('WebApp Infoway')
            ->setTitle($proposal->proposal_title)
            ->setSubject('Enterprise Digital Transformation Proposal')
            ->setDescription(
                'Page-for-page Word export of proposal version '
                . $proposal->version
            )
            ->setCategory('Business Proposal');

        /*
         * A4 dimensions in twentieths of a point (twips):
         * 210 mm x 297 mm = approximately 11906 x 16838 twips.
         *
         * Small 20-twip margins prevent Word from creating blank overflow pages.
         */
        $sectionStyle = [
            'pageSizeW' => 11906,
            'pageSizeH' => 16838,
            'marginTop' => 20,
            'marginRight' => 20,
            'marginBottom' => 20,
            'marginLeft' => 20,
            'headerHeight' => 0,
            'footerHeight' => 0,
        ];

        foreach ($pageImages as $index => $pageImage) {
            $section = $word->addSection($sectionStyle);

            /*
             * 793 x 1121 pixels is the A4 ratio and fits safely inside the
             * section without forcing an extra blank Word page.
             */
            $section->addImage(
                $pageImage,
                [
                    'width' => 793,
                    'height' => 1121,
                    'ratio' => false,
                    'wrappingStyle' => 'inline',
                    'alignment' => 'center',
                    'marginTop' => 0,
                    'marginLeft' => 0,
                ]
            );
        }

        IOFactory::createWriter(
            $word,
            'Word2007'
        )->save($outputPath);
    }

    private function isValidDocx(string $path): bool
    {
        if (!is_file($path) || filesize($path) === 0) {
            return false;
        }

        $zip = new ZipArchive();

        if ($zip->open($path) !== true) {
            return false;
        }

        $requiredFiles = [
            '[Content_Types].xml',
            'word/document.xml',
        ];

        foreach ($requiredFiles as $requiredFile) {
            if ($zip->locateName($requiredFile) === false) {
                $zip->close();

                return false;
            }
        }

        $documentXml = $zip->getFromName(
            'word/document.xml'
        );

        $zip->close();

        if (!is_string($documentXml) || $documentXml === '') {
            return false;
        }

        $previous = libxml_use_internal_errors(true);

        $dom = new DOMDocument(
            '1.0',
            'UTF-8'
        );

        $valid = $dom->loadXML(
            $documentXml,
            LIBXML_NONET
        );

        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        return $valid;
    }
}