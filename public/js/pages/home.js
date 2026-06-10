document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.querySelector('form[action*="#events"]');
    const searchInput = searchForm?.querySelector('input[name="q"]');
    const categoryContainer = document.querySelector('.flex-wrap.gap-2');
    const eventsSection = document.getElementById('events');
    
    if (!searchForm || !eventsSection) return;

    let debounceTimer;

    function fetchEvents(url) {
        const wrapper = document.getElementById('events-wrapper');
        if (wrapper) wrapper.style.opacity = '0.5';

        fetch(url)
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                const newWrapper = doc.getElementById('events-wrapper');
                if (wrapper && newWrapper) {
                    wrapper.innerHTML = newWrapper.innerHTML;
                    wrapper.style.opacity = '1';
                }
                
                if (categoryContainer) {
                    const newCategoryContainer = doc.querySelector('#events .flex-wrap.gap-2');
                    if (newCategoryContainer) {
                        categoryContainer.innerHTML = newCategoryContainer.innerHTML;
                        attachCategoryListeners();
                    }
                }
                
                attachPaginationListeners();
                initScrollAnimations();
                
                window.history.pushState({}, '', url);
            });
    }

    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const url = new URL(searchForm.action);
        url.search = new URLSearchParams(new FormData(searchForm)).toString();
        fetchEvents(url.toString());
    });

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const url = new URL(window.location.href);
                url.searchParams.set('q', searchInput.value);
                fetchEvents(url.toString());
            }, 400);
        });
    }

    function attachCategoryListeners() {
        if (!categoryContainer) return;
        const pills = categoryContainer.querySelectorAll('a');
        pills.forEach(pill => {
            pill.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('href');
                
                const urlObj = new URL(url, window.location.origin);
                const catValue = urlObj.searchParams.get('kategori') || '';
                
                let hiddenInput = searchForm.querySelector('input[name="kategori"]');
                if (!hiddenInput) {
                    hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'kategori';
                    searchForm.appendChild(hiddenInput);
                }
                hiddenInput.value = catValue;

                fetchEvents(url);
            });
        });
    }

    function attachPaginationListeners() {
        const paginationLinks = document.querySelectorAll('.pagination-link');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('href');
                fetchEvents(url);
                // Scroll up smoothly to the events section
                document.getElementById('events').scrollIntoView({ behavior: 'smooth' });
            });
        });
    }

    function initScrollAnimations() {
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    // Stagger the animation slightly for items appearing together
                    setTimeout(() => {
                        entry.target.classList.remove('opacity-0', 'translate-y-8');
                        entry.target.classList.add('opacity-100', 'translate-y-0');
                    }, index * 100);
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.reveal-item').forEach(item => {
            observer.observe(item);
        });
    }

    attachCategoryListeners();
    attachPaginationListeners();
    initScrollAnimations();
});
