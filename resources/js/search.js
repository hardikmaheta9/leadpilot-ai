document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('globalSearch');
    const resultsBox = document.getElementById('searchResults');

    if (!input || !resultsBox) {
        return;
    }

    let debounceTimer = null;
    let activeIndex = -1;
    let currentResults = [];
    let controller = null;

    const icons = {
        company: 'fa-solid fa-building',
        contact: 'fa-solid fa-address-book',
        task: 'fa-solid fa-list-check',
        meeting: 'fa-solid fa-calendar-days',
        call: 'fa-solid fa-phone',
        note: 'fa-solid fa-note-sticky',
        document: 'fa-solid fa-file-lines',
        default: 'fa-solid fa-magnifying-glass',
    };

    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function highlight(value, query) {
        const safeValue = escapeHtml(value);
        const safeQuery = escapeHtml(query);

        if (!safeQuery) {
            return safeValue;
        }

        const expression = new RegExp(
            `(${safeQuery.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`,
            'ig'
        );

        return safeValue.replace(expression, '<mark>$1</mark>');
    }

    function hideResults() {
        resultsBox.style.display = 'none';
        resultsBox.innerHTML = '';
        activeIndex = -1;
        currentResults = [];
    }

    function showLoading() {
        resultsBox.innerHTML = `
            <div class="lp-search-state">
                <span class="spinner-border spinner-border-sm"></span>
                <span>Searching...</span>
            </div>
        `;

        resultsBox.style.display = 'block';
    }

    function showEmpty() {
        resultsBox.innerHTML = `
            <div class="lp-search-state">
                <i class="fa-solid fa-magnifying-glass"></i>
                <span>No results found.</span>
            </div>
        `;

        resultsBox.style.display = 'block';
    }

    function normalizeResults(payload) {
        if (Array.isArray(payload)) {
            return payload;
        }

        const normalized = [];

        Object.entries(payload || {}).forEach(([type, items]) => {
            if (!Array.isArray(items)) {
                return;
            }

            items.forEach((item) => {
                normalized.push({
                    ...item,
                    type: item.type || type.replace(/s$/, ''),
                });
            });
        });

        return normalized;
    }

    function renderResults(results, query) {
        currentResults = results;
        activeIndex = -1;

        if (!results.length) {
            showEmpty();
            return;
        }

        resultsBox.innerHTML = results.map((item, index) => {
            const type = String(item.type || 'default').toLowerCase();
            const icon = icons[type] || icons.default;
            const title = item.title || item.name || item.label || 'Untitled';
            const subtitle =
                item.subtitle ||
                item.description ||
                item.company_name ||
                item.meta ||
                '';
            const url = item.url || item.href || '#';

            return `
                <a
                    href="${escapeHtml(url)}"
                    class="lp-search-result"
                    data-search-index="${index}"
                >
                    <span class="lp-search-result-icon lp-search-result-${escapeHtml(type)}">
                        <i class="${icon}"></i>
                    </span>

                    <span class="lp-search-result-content">
                        <strong>${highlight(title, query)}</strong>
                        ${subtitle
                            ? `<small>${highlight(subtitle, query)}</small>`
                            : ''}
                    </span>

                    <span class="lp-search-result-type">
                        ${escapeHtml(type)}
                    </span>
                </a>
            `;
        }).join('');

        resultsBox.style.display = 'block';
    }

    async function runSearch(query) {
        if (controller) {
            controller.abort();
        }

        controller = new AbortController();

        showLoading();

        try {
            const response = await fetch(
                `/search?q=${encodeURIComponent(query)}`,
                {
                    headers: {
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    signal: controller.signal,
                }
            );

            if (!response.ok) {
                throw new Error('Search request failed.');
            }

            const payload = await response.json();
            const results = normalizeResults(payload);

            renderResults(results, query);
        } catch (error) {
            if (error.name === 'AbortError') {
                return;
            }

            resultsBox.innerHTML = `
                <div class="lp-search-state lp-search-state-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>Unable to search right now.</span>
                </div>
            `;

            resultsBox.style.display = 'block';
        }
    }

    function updateActiveResult(nextIndex) {
        const resultElements =
            resultsBox.querySelectorAll('[data-search-index]');

        if (!resultElements.length) {
            return;
        }

        resultElements.forEach((element) => {
            element.classList.remove('active');
        });

        activeIndex = nextIndex;

        if (activeIndex < 0) {
            activeIndex = resultElements.length - 1;
        }

        if (activeIndex >= resultElements.length) {
            activeIndex = 0;
        }

        const activeElement = resultElements[activeIndex];

        activeElement.classList.add('active');
        activeElement.scrollIntoView({
            block: 'nearest',
        });
    }

    input.addEventListener('input', function () {
        const query = input.value.trim();

        window.clearTimeout(debounceTimer);

        if (query.length < 2) {
            hideResults();
            return;
        }

        debounceTimer = window.setTimeout(function () {
            runSearch(query);
        }, 300);
    });

    input.addEventListener('keydown', function (event) {
        if (resultsBox.style.display === 'none') {
            return;
        }

        if (event.key === 'ArrowDown') {
            event.preventDefault();
            updateActiveResult(activeIndex + 1);
        }

        if (event.key === 'ArrowUp') {
            event.preventDefault();
            updateActiveResult(activeIndex - 1);
        }

        if (event.key === 'Enter' && activeIndex >= 0) {
            event.preventDefault();

            const activeElement =
                resultsBox.querySelector(
                    `[data-search-index="${activeIndex}"]`
                );

            if (activeElement) {
                window.location.href = activeElement.href;
            }
        }

        if (event.key === 'Escape') {
            hideResults();
            input.blur();
        }
    });

    document.addEventListener('click', function (event) {
        if (
            !input.contains(event.target) &&
            !resultsBox.contains(event.target)
        ) {
            hideResults();
        }
    });
});