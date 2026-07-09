document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('globalSearch');
    const results = document.getElementById('searchResults');

    let selectedIndex = -1;

    if (!searchInput || !results) {
        return;
    }

    searchInput.addEventListener('keyup', async function () {

        const q = this.value.trim();

        if (q.length < 2) {
            results.style.display = 'none';
            results.innerHTML = '';
            selectedIndex = -1;
            return;
        }

        try {

            const response = await fetch('/search?q=' + encodeURIComponent(q));
            const data = await response.json();

            let html = '';

            /* ==========================================
               Companies
            ========================================== */

            if (data.companies && data.companies.length > 0) {

                html += `
                    <div class="list-group-item fw-bold bg-light">
                        🏢 Companies
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

            /* ==========================================
               Contacts
            ========================================== */

            if (data.contacts && data.contacts.length > 0) {

                html += `
                    <div class="list-group-item fw-bold bg-light">
                        👤 Contacts
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

            /* ==========================================
               No Results
            ========================================== */

            if (html === '') {

                html = `
                    <div class="list-group-item text-center text-muted py-4">
                        No results found
                    </div>
                `;

            }

            results.innerHTML = html;
            results.style.display = 'block';
            selectedIndex = -1;

        }
        catch (error) {

            console.error(error);

        }

    });

    /* ==========================================
       Close Search
    ========================================== */

    document.addEventListener('click', function (e) {

        if (!searchInput.contains(e.target) &&
            !results.contains(e.target)) {

            results.style.display = 'none';

        }

    });

    /* ==========================================
       Keyboard Navigation
    ========================================== */

    document.addEventListener('keydown', function (e) {

        const items = results.querySelectorAll('a.list-group-item');

        if (items.length === 0) {
            return;
        }

        if (e.key === 'ArrowDown') {

            if (results.style.display === 'none') return;

            e.preventDefault();

            selectedIndex++;

            if (selectedIndex >= items.length)
                selectedIndex = 0;

            items.forEach(item => item.classList.remove('lp-search-selected'));

            items[selectedIndex].classList.add('lp-search-selected');

            items[selectedIndex].scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });

        }

        else if (e.key === 'ArrowUp') {

            if (results.style.display === 'none') return;

            e.preventDefault();

            selectedIndex--;

            if (selectedIndex < 0)
                selectedIndex = items.length - 1;

            items.forEach(item => item.classList.remove('lp-search-selected'));

            items[selectedIndex].classList.add('lp-search-selected');

            items[selectedIndex].scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });

        }

        else if (e.key === 'Enter') {

            if (selectedIndex >= 0 && items[selectedIndex]) {

                e.preventDefault();
                items[selectedIndex].click();

            }

        }

        else if (e.key === 'Escape') {

            results.style.display = 'none';
            searchInput.blur();

        }

        else if (e.ctrlKey && e.key.toLowerCase() === 'k') {

            e.preventDefault();

            searchInput.focus();
            searchInput.select();

        }

    });

});