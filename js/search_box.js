
    // Helper: Calculate Levenshtein distance (basic fuzzy match)
    function levenshtein(a, b) {
        if (a.length === 0) return b.length;
        if (b.length === 0) return a.length;
        const matrix = [];
        let i;
        for (i = 0; i <= b.length; i++) {
            matrix[i] = [i];
        }
        let j;
        for (j = 0; j <= a.length; j++) {
            matrix[0][j] = j;
        }
        for (i = 1; i <= b.length; i++) {
            for (j = 1; j <= a.length; j++) {
                if (b.charAt(i - 1) === a.charAt(j - 1)) {
                    matrix[i][j] = matrix[i - 1][j - 1];
                } else {
                    matrix[i][j] = Math.min(
                        matrix[i - 1][j - 1] + 1, // substitution
                        matrix[i][j - 1] + 1,     // insertion
                        matrix[i - 1][j] + 1      // deletion
                    );
                }
            }
        }
        return matrix[b.length][a.length];
    }

    // Filter posts with fuzzy fallback
    function filterPosts(query) {
        query = query.trim().toLowerCase();
        const posts = document.querySelectorAll('.my_posts .post');

        // If search box is empty, show all posts (default)
        if (!query) {
            posts.forEach(post => {
                post.style.display = '';
            });
            return;
        }

        let found = false;
        let fuzzyMatches = [];

        posts.forEach(post => {
            const postText = post.querySelector('.post_text')?.innerText.toLowerCase() || '';
            const username = post.querySelector('.user_name')?.innerText.toLowerCase() || '';
            const postDate = post.querySelector('.post_date')?.innerText.toLowerCase() || '';
            const postTime = post.querySelector('.post_time')?.innerText.toLowerCase() || '';
            const searchable = postText + ' ' + username + ' ' + postDate + ' ' + postTime;

            if (searchable.includes(query)) {
                post.style.display = '';
                found = true;
            } else {
                post.style.display = 'none';
                // For fuzzy: store distance if query is not empty
                let minDist = Math.min(
                    levenshtein(query, postText),
                    levenshtein(query, username),
                    levenshtein(query, postDate),
                    levenshtein(query, postTime)
                );
                fuzzyMatches.push({ post, minDist });
            }
        });

        // If no exact match, show closest fuzzy matches (distance <= 5)
        if (!found && query) {
            fuzzyMatches.sort((a, b) => a.minDist - b.minDist);
            let minDist = fuzzyMatches.length > 0 ? fuzzyMatches[0].minDist : null;
            fuzzyMatches.forEach(match => {
                if (match.minDist === minDist && minDist <= 5) {
                    match.post.style.display = '';
                }
            });
        }
    }

    // Sanitize input and trigger filter
    function sanitizeSearchInput(input) {
        input.value = input.value.replace(/[<>"'`\\]/g, '');
        if (input.value.length > 100) {
            input.value = input.value.substring(0, 100);
        }
        filterPosts(input.value);
    }

    // Optionally, trigger filter on page load if search_box has value
    document.addEventListener('DOMContentLoaded', function() {
        const searchBox = document.getElementById('search_box');
        if (searchBox && searchBox.value) {
            filterPosts(searchBox.value);
        }
    });






















 