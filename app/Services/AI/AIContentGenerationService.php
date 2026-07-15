<?php

namespace App\Services\AI;

use App\Models\Company;
use App\Models\CompanyAiGeneratedContent;
use Illuminate\Support\Collection;

class AIContentGenerationService
{
    public function generateAll(Company $company): Collection
    {
        $company->loadMissing([
            'aiProfile',
            'websiteAnalysis',
            'aiRecommendations',
            'aiSalesConsultant',
            'contacts',
        ]);

        return collect([
            $this->generateColdEmail($company),
            $this->generateFollowupEmail($company),
            $this->generateLinkedInMessage($company),
            $this->generateWhatsappMessage($company),
            $this->generateCallScript($company),
            $this->generateMeetingAgenda($company),
            $this->generateElevatorPitch($company),
        ])->filter()->values();
    }

    public function generateByType(
        Company $company,
        string $contentType
    ): CompanyAiGeneratedContent {
        $company->loadMissing([
            'aiProfile',
            'websiteAnalysis',
            'aiRecommendations',
            'aiSalesConsultant',
            'contacts',
        ]);

        return match ($contentType) {
            'cold_email' =>
                $this->generateColdEmail($company),

            'followup_email' =>
                $this->generateFollowupEmail($company),

            'linkedin_message' =>
                $this->generateLinkedInMessage($company),

            'whatsapp_message' =>
                $this->generateWhatsappMessage($company),

            'call_script' =>
                $this->generateCallScript($company),

            'meeting_agenda' =>
                $this->generateMeetingAgenda($company),

            'elevator_pitch' =>
                $this->generateElevatorPitch($company),

            default => throw new \InvalidArgumentException(
                "Unsupported content type: {$contentType}"
            ),
        };
    }

    protected function generateColdEmail(
        Company $company
    ): CompanyAiGeneratedContent {
        $consultant = $company->aiSalesConsultant;

        $recipientName = $this->recipientName($company);

        $primaryService = $this->primaryService($company);

        $subject = "Ideas to improve {$company->company_name}'s digital growth";

        $content = "Hello {$recipientName},\n\n"
            . "I reviewed {$company->company_name}'s online presence and noticed a few opportunities that may help improve lead generation, customer engagement and operational efficiency.\n\n"
            . "The strongest opportunity appears to be {$primaryService}.\n\n"
            . $this->consultantSummary($consultant)
            . "\n\n"
            . "Would you be open to a short 15-minute discussion to explore the most practical improvements for your business?\n\n"
            . "Best regards";

        return $this->storeContent(
            company: $company,
            contentType: 'cold_email',
            title: 'Personalized Cold Email',
            subject: $subject,
            content: $content
        );
    }

    protected function generateFollowupEmail(
        Company $company
    ): CompanyAiGeneratedContent {
        $recipientName = $this->recipientName($company);

        $primaryService = $this->primaryService($company);

        $subject = "Following up on {$company->company_name}";

        $content = "Hello {$recipientName},\n\n"
            . "I wanted to follow up on my earlier message regarding {$company->company_name}.\n\n"
            . "Based on the review, {$primaryService} looks like the highest-impact starting point. A phased approach could help deliver measurable improvements without requiring a large upfront implementation.\n\n"
            . "Would this week or next week be convenient for a brief discussion?\n\n"
            . "Best regards";

        return $this->storeContent(
            company: $company,
            contentType: 'followup_email',
            title: 'Follow-up Email',
            subject: $subject,
            content: $content
        );
    }

    protected function generateLinkedInMessage(
        Company $company
    ): CompanyAiGeneratedContent {
        $recipientName = $this->recipientName($company);

        $primaryService = $this->primaryService($company);

        $content = "Hi {$recipientName},\n\n"
            . "I recently reviewed {$company->company_name}'s digital presence and noticed a few opportunities around {$primaryService}.\n\n"
            . "I believe there may be a practical way to improve lead generation and business efficiency. Would you be open to a brief conversation?";

        return $this->storeContent(
            company: $company,
            contentType: 'linkedin_message',
            title: 'LinkedIn Outreach Message',
            subject: null,
            content: $content
        );
    }

    protected function generateWhatsappMessage(
        Company $company
    ): CompanyAiGeneratedContent {
        $recipientName = $this->recipientName($company);

        $primaryService = $this->primaryService($company);

        $content = "Hi {$recipientName}, I reviewed {$company->company_name}'s website and found a few improvement opportunities, especially around {$primaryService}. Would you be open to a short 15-minute discussion?";

        return $this->storeContent(
            company: $company,
            contentType: 'whatsapp_message',
            title: 'WhatsApp Outreach Message',
            subject: null,
            content: $content
        );
    }

    protected function generateCallScript(
        Company $company
    ): CompanyAiGeneratedContent {
        $primaryService = $this->primaryService($company);

        $topPainPoint = $this->topPainPoint($company);

        $content = "INTRODUCTION\n"
            . "Hello, this is [Your Name]. I recently reviewed {$company->company_name}'s digital presence and wanted to share a few observations.\n\n"
            . "OPENING CONTEXT\n"
            . "One area that stood out was {$primaryService}.\n\n"
            . "OBSERVED PAIN POINT\n"
            . "{$topPainPoint}\n\n"
            . "DISCOVERY QUESTIONS\n"
            . "• How are enquiries currently managed?\n"
            . "• Which digital or operational process creates the most difficulty?\n"
            . "• Are follow-ups tracked manually or through a system?\n"
            . "• Is there an active plan to improve website performance or lead generation?\n\n"
            . "VALUE PROPOSITION\n"
            . "We can take a phased approach, starting with the highest-impact improvement and expanding only after measurable results.\n\n"
            . "CLOSE\n"
            . "Would it make sense to schedule a short discovery meeting with the relevant decision-maker?";

        return $this->storeContent(
            company: $company,
            contentType: 'call_script',
            title: 'Sales Call Script',
            subject: null,
            content: $content
        );
    }

    protected function generateMeetingAgenda(
        Company $company
    ): CompanyAiGeneratedContent {
        $services = $this->serviceBundle($company);

        $content = "MEETING AGENDA — {$company->company_name}\n\n"
            . "1. Current business and sales process\n"
            . "2. Existing website, CRM and operational systems\n"
            . "3. Main pain points and business priorities\n"
            . "4. Review of identified opportunities\n"
            . "5. Recommended service bundle:\n"
            . $this->bulletList($services)
            . "\n6. Suggested implementation phases\n"
            . "7. Budget and decision-making process\n"
            . "8. Next steps and responsibilities";

        return $this->storeContent(
            company: $company,
            contentType: 'meeting_agenda',
            title: 'Discovery Meeting Agenda',
            subject: null,
            content: $content
        );
    }

    protected function generateElevatorPitch(
        Company $company
    ): CompanyAiGeneratedContent {
        $primaryService = $this->primaryService($company);

        $dealValue = (int) (
            $company->aiSalesConsultant?->estimated_deal_value
            ?? 0
        );

        $content = "{$company->company_name} appears to have a strong opportunity for {$primaryService}. "
            . "LeadPilot identified several digital and operational improvements that could increase lead generation, improve customer engagement and reduce manual work. "
            . (
                $dealValue > 0
                    ? "The estimated combined opportunity value is approximately ₹"
                        . number_format($dealValue)
                        . '.'
                    : ''
            );

        return $this->storeContent(
            company: $company,
            contentType: 'elevator_pitch',
            title: 'Elevator Pitch',
            subject: null,
            content: $content
        );
    }

    private function storeContent(
        Company $company,
        string $contentType,
        string $title,
        ?string $subject,
        string $content
    ): CompanyAiGeneratedContent {
        $latest = CompanyAiGeneratedContent::where(
            'company_uuid',
            $company->uuid
        )
            ->where('content_type', $contentType)
            ->latest('version')
            ->first();

        $nextVersion = $latest
            ? $latest->version + 1
            : 1;

        $regeneratedCount = $latest
            ? $latest->regenerated_count + 1
            : 0;

        return CompanyAiGeneratedContent::create([
            'company_uuid' => $company->uuid,
            'content_type' => $contentType,
            'title' => $title,
            'subject' => $subject,
            'content' => $content,
            'generator' => 'template',
            'language' => 'en',
            'version' => $nextVersion,
            'regenerated_count' => $regeneratedCount,
            'generated_at' => now(),
        ]);
    }

    private function recipientName(
        Company $company
    ): string {
        $contact = $company->contacts
            ->sortByDesc(function ($contact) {
                $designation = strtolower(
                    (string) ($contact->designation ?? '')
                );

                return match (true) {
                    str_contains($designation, 'owner') => 100,
                    str_contains($designation, 'founder') => 95,
                    str_contains($designation, 'director') => 90,
                    str_contains($designation, 'ceo') => 90,
                    str_contains($designation, 'manager') => 70,
                    default => 10,
                };
            })
            ->first();

        $name = trim(
            ($contact?->first_name ?? '')
            . ' '
            . ($contact?->last_name ?? '')
        );

        return $name !== ''
            ? $name
            : 'there';
    }

    private function primaryService(
        Company $company
    ): string {
        return $company->aiRecommendations
            ->sortByDesc('priority_score')
            ->pluck('recommended_service')
            ->filter()
            ->first()
            ?? 'digital transformation';
    }

    private function topPainPoint(
        Company $company
    ): string {
        return $company->aiRecommendations
            ->sortByDesc('priority_score')
            ->pluck('reason')
            ->filter()
            ->first()
            ?? 'The current digital setup may be limiting growth and sales efficiency.';
    }

    private function serviceBundle(
        Company $company
    ): array {
        return $company->aiSalesConsultant?->service_bundle
            ?? $company->aiRecommendations
                ->sortByDesc('priority_score')
                ->pluck('recommended_service')
                ->filter()
                ->unique()
                ->take(6)
                ->values()
                ->all();
    }

    private function consultantSummary(
        $consultant
    ): string {
        if (!$consultant) {
            return 'The review indicates there is room to improve the company’s digital maturity and sales process.';
        }

        return $consultant->executive_summary
            ?: 'The review indicates there is room to improve the company’s digital maturity and sales process.';
    }

    private function bulletList(
        array $items
    ): string {
        if (empty($items)) {
            return "• No service bundle generated yet.\n";
        }

        return collect($items)
            ->map(fn ($item) => '• ' . $item)
            ->implode("\n");
    }
}