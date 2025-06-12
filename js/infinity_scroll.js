function setupInfiniteScroll(containerSelector, loaderSelector) {
    const container = document.querySelector(containerSelector);
    if (!container) return;

    const posts = Array.from(container.querySelectorAll('.my_posts .post'));
    const loader = document.querySelector(loaderSelector);
    // Find the search box inside this container (scoped search)
    const searchBox = container.querySelector('#search_box');
    const batchSize = 15;
    let visibleCount = 0;
    let loading = false;
    let enabled = true;

    function showInitialPosts() {
        posts.forEach((post, i) => {
            post.style.display = i < batchSize ? '' : 'none';
        });
        visibleCount = Math.min(batchSize, posts.length);
    }

    function showNextBatch() {
        if (loading || visibleCount >= posts.length || !enabled) return;
        loading = true;
        if (loader) {
            loader.style.display = 'flex';
            loader.style.justifyContent = 'center';
            loader.style.alignItems = 'center';
        }
        setTimeout(() => {
            for (let i = visibleCount; i < visibleCount + batchSize && i < posts.length; i++) {
                posts[i].style.display = '';
            }
            visibleCount += batchSize;
            if (loader) loader.style.display = 'none';
            loading = false;
        }, 700);
    }

    function onScroll() {
        if (loading || visibleCount >= posts.length || !enabled) return;
        const rect = container.getBoundingClientRect();
        if (rect.top < window.innerHeight && rect.bottom > 0) {
            const scrollY = window.scrollY || window.pageYOffset;
            const viewport = window.innerHeight;
            const fullHeight = document.body.offsetHeight;
            if (scrollY + viewport > fullHeight - 200) {
                showNextBatch();
            }
        }
    }

    // Disable infinite scroll when this section's search box has any value (including hashtag click)
    if (searchBox) {
        // Always disable infinite scroll on any input
        searchBox.addEventListener('input', function () {
            enabled = searchBox.value.trim() === '';
            if (enabled) showInitialPosts();
        });

        // Also disable infinite scroll on focus (for hashtag click or programmatic input)
        searchBox.addEventListener('focus', function () {
            if (searchBox.value.trim() !== '') {
                enabled = false;
            }
        });

        // Optionally, disable on any change (for programmatic value set)
        const observer = new MutationObserver(function () {
            if (searchBox.value.trim() !== '') {
                enabled = false;
            }
        });
        observer.observe(searchBox, { attributes: true, attributeFilter: ['value'] });
    }

    showInitialPosts();
    window.addEventListener('scroll', onScroll);
}

// Initialize for all sections
document.addEventListener('DOMContentLoaded', function () {
    setupInfiniteScroll('#open_scrolls_contents', '#infinite-loader-open');
    setupInfiniteScroll('#my_timeline', '#infinite-loader-timeline');
    setupInfiniteScroll('#my_posts_contents', '#infinite-loader-timeline');
});