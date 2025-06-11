   // This script should be placed in ../js/search_following.js and is loaded in your header.php

// Levenshtein distance for fuzzy matching
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
                    matrix[i - 1][j - 1] + 1,
                    matrix[i][j - 1] + 1,
                    matrix[i - 1][j] + 1
                );
            }
        }
    }
    return matrix[b.length][a.length];
}

function filterFollowings(query) {
    query = query.trim().toLowerCase();
    const posts = document.querySelectorAll('.followings .post');

    // Show all if search is empty
    if (!query) {
        posts.forEach(post => post.style.display = '');
        return;
    }

    let found = false;
    let fuzzyMatches = [];

    posts.forEach(post => {
        const username = post.querySelector('.username')?.innerText.toLowerCase() || '';
        if (username.includes(query)) {
            post.style.display = '';
            found = true;
        } else {
            post.style.display = 'none';
            let dist = levenshtein(query, username);
            fuzzyMatches.push({ post, dist });
        }
    });

    // If no exact match, show closest fuzzy matches (distance <= 3)
    if (!found && query) {
        fuzzyMatches.sort((a, b) => a.dist - b.dist);
        let minDist = fuzzyMatches.length > 0 ? fuzzyMatches[0].dist : null;
        fuzzyMatches.forEach(match => {
            if (match.dist === minDist && minDist <= 3) {
                match.post.style.display = '';
            }
        });
    }
}

function sanitizeSearchFollowingInput(input) {
    input.value = input.value.replace(/[<>"'`\\]/g, '');
    if (input.value.length > 100) {
        input.value = input.value.substring(0, 100);
    }
    filterFollowings(input.value);
}

document.addEventListener('DOMContentLoaded', function() {
    const searchFollowing = document.getElementById('search_following');
    if (searchFollowing) {
        searchFollowing.addEventListener('input', function() {
            sanitizeSearchFollowingInput(this);
        });
        // Initial filter if value exists
        if (searchFollowing.value) {
            filterFollowings(searchFollowing.value);
        }
    }
});