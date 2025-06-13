document.addEventListener('DOMContentLoaded', function () {
    // List of common TLDs for URL detection
    const tldList = [
        '.com', '.net', '.org', '.io', '.co', '.gov', '.edu', '.info', '.biz', '.me', '.us', '.uk', '.ng', '.xyz',
        '.site', '.online', '.store', '.tech', '.app', '.dev', '.ai', '.ca', '.in', '.au', '.za', '.fr', '.de', '.es',
        '.it', '.ru', '.jp', '.cn', '.br', '.tv', '.cc', '.ly', '.fm', '.ws', '.mobi', '.pro', '.name', '.jobs',
        '.museum', '.travel', '.int', '.mil', '.arpa'
    ];

    // Regex for hashtags and URLs
    const hashtagRegex = /#(\w+)/g;
    const urlRegex = /\b((https?:\/\/)?[\w-]+(\.[\w-]+)+([/?#][^\s<]*)?)/gi;

    function linkifyText(text) {
        // Highlight URLs (not clickable, just add class)
        text = text.replace(urlRegex, function (match) {
            if (!tldList.some(tld => match.toLowerCase().includes(tld))) return match;
            return `<code class="hyperlink">${match}</code>`;
        });

        // Highlight hashtags (inline)
        text = text.replace(hashtagRegex, function (match, tag) {
            return `<code class="hyperlink hashtag-link" data-hashtag="${tag}">${match}</code>`;
        });

        return text;
    }

    document.querySelectorAll('.post_text').forEach(function (container) {
        if (!container.dataset.linkified) {
            let text = container.textContent;
            container.innerHTML = linkifyText(text);
            container.dataset.linkified = "true";
        }
    });

    // Hashtag click handler: filter posts in .my_posts
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('hashtag-link')) {
            const tag = e.target.dataset.hashtag.toLowerCase();
            const searchBox = document.getElementById('search_box');
            if (searchBox) {
                searchBox.value = tag;
                if (typeof sanitizeSearchInput === 'function') {
                    sanitizeSearchInput(searchBox);
                } else if (typeof filterPosts === 'function') {
                    filterPosts(tag);
                }
            }
            e.preventDefault();
        }
    });
});