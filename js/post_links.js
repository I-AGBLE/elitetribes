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
    const urlRegex = /\b((https?:\/\/)?[\w-]+(\.[\w-]+)+([/?#][^\s]*)?)/gi;

    // Linkify function for post text
    function linkifyText(text) {
        // Linkify URLs first
        text = text.replace(urlRegex, function (match) {
            let url = match;
            // Only treat as URL if it ends with a known TLD
            if (!tldList.some(tld => url.toLowerCase().includes(tld))) return match;
            if (!/^https?:\/\//i.test(url)) {
                url = 'http://' + url;
            }
            return `<a href="${url}" class="hyperlink" target="_blank" rel="noopener noreferrer">${match}</a>`;
        });

        // Linkify hashtags (use <a> with class "hyperlink" instead of <span>)
        text = text.replace(hashtagRegex, function (match, tag) {
            return `<a href="javascript:void(0);" class="hyperlink hashtag-link" data-hashtag="${tag}">${match}</a>`;
        });

        return text;
    }

    // Process all .post_text containers
    document.querySelectorAll('.post_text').forEach(function (container) {
        let html = container.innerHTML;
        // Only process if not already linkified
        if (!container.dataset.linkified) {
            html = linkifyText(html);
            container.innerHTML = html;
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
                // Trigger your filter logic (sanitizeSearchInput or filterPosts)
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