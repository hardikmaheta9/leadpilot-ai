document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('globalSearch');
    const results = document.getElementById('searchResults');

    if (!searchInput || !results) {
        return;
    }

    searchInput.addEventListener('keyup', async function () {

        const q = this.value.trim();

        if (q.length < 2) {
            results.style.display = 'none';
            results.innerHTML = '';
            return;
        }

        try {

            const response = await fetch('/search?q=' + encodeURIComponent(q));

            const data = await response.json();

            let html = '';

            /* ============================
               Companies
            ============================= */

            if (data.companies && data.companies.length > 0) {

                html += `
                    <div class="list-group-item active">
                        Companies
                    </div>
                `;

                data.companies.forEach(company => {

                    html += `
                        <a href="/companies/${company.uuid}"
                           class="list-group-item list-group-item-action">

                            <div class="fw-bold">
                                🏢 ${company.company_name}
                            </div>

                            <small class="text-muted">
                                ${company.industry ?? ''}
                                ${company.city ?? ''}
                            </small>

                        </a>
                    `;

                });

            }

            /* ============================
               Contacts
            ============================= */

            if (data.contacts && data.contacts.length > 0) {

                html += `
                    <div class="list-group-item active">
                        Contacts
                    </div>
                `;

                data.contacts.forEach(contact => {

                    html += `
                        <a href="/companies/${contact.company_uuid}?tab=contacts"
                        class="list-group-item list-group-item-action">

                            <div class="fw-bold">
                                👤 ${contact.first_name} ${contact.last_name ?? ''}
                            </div>

                            <small class="text-muted">
                                ${contact.designation ?? ''}
                            </small>

                        </a>
                       `;

                });

            }

            /* ============================
               No Results
            ============================= */

            if (html === '') {

                html = `
                    <div class="list-group-item text-center text-muted">
                        No results found
                    </div>
                `;

            }

            results.innerHTML = html;
            results.style.display = 'block';

        }
        catch (error) {

            console.error(error);

        }

    });

    document.addEventListener('click', function (e) {

        if (!searchInput.contains(e.target) &&
            !results.contains(e.target)) {

            results.style.display = 'none';

        }

    });

});