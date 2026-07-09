<div class="lp-dashboard-hero">

    <div class="row align-items-center">

        <div class="col-lg-8">

            <h1>
                Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 18 ? 'Afternoon' : 'Evening') }},
                {{ explode(' ', auth()->user()->name)[0] }} 👋
            </h1>

            <p>
                Welcome back to LeadPilot AI.
                Your business command center is ready.
            </p>

            <div class="lp-hero-actions">

                <a href="{{ route('companies.create') }}"
                   class="lp-btn lp-btn-primary">

                    <i class="fa-solid fa-plus"></i>

                    Add Company

                </a>

                <a href="#"
                   class="lp-btn lp-btn-light">

                    <i class="fa-solid fa-wand-magic-sparkles"></i>

                    Run AI Scan

                </a>

            </div>

        </div>

        <div class="col-lg-4 text-end">

            <h2 style="font-weight:900;font-size:64px;">
                {{ now()->format('d') }}
            </h2>

            <div style="color:#CBD5E1;">
                {{ now()->format('F Y') }}
            </div>

        </div>

    </div>

</div>