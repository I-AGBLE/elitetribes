// Title Bar Text Animation
const defaultTitleText = "From Void To Signal ...";
const animatedText = "Be Heard!";
let isAnimating = true;

function variantTitleText() {
  if (isAnimating) {
    document.title = animatedText;
  } else {
    document.title = defaultTitleText;
  }
  isAnimating = !isAnimating;
}
setInterval(variantTitleText, 6000);



// Select the buttons
const followBtn = document.getElementById("default_btn");
const followingBtn = document.getElementById("danger_btn");

// Toggle function to switch between Follow and Following
followBtn.addEventListener("click", function (event) {
  event.preventDefault(); // Prevent default link behavior
  
  // Hide the follow button and show the following button
  followBtn.style.display = "none";
  followingBtn.style.display = "inline-block";
});

followingBtn.addEventListener("click", function (event) {
  event.preventDefault(); // Prevent default link behavior
  
  // Hide the following button and show the follow button
  followingBtn.style.display = "none";
  followBtn.style.display = "inline-block";
});





// Select all like_containers that hold the like/unlike icons
const like_containers = document.querySelectorAll('.like_icons');

like_containers.forEach(function(like_container) {
  // Get the like and unlike icons within the current like_container
  const likeIcon = like_container.querySelector('.like_icon');
  const unlikeIcon = like_container.querySelector('.like_icon_is_clicked');

  // Handle the like icon click event
  likeIcon.addEventListener('click', function(event) {
    event.preventDefault();
    
    // Toggle the icons: hide like, show unlike
    likeIcon.style.display = 'none';
    unlikeIcon.style.display = 'inline-block';  // Show the unlike icon
 });

  // Handle the unlike icon click event
  unlikeIcon.addEventListener('click', function(event) {
    event.preventDefault();
    
    // Toggle the icons: hide unlike, show like
    unlikeIcon.style.display = 'none';
    likeIcon.style.display = 'inline-block';  // Show the like icon
  });
});




document.addEventListener('DOMContentLoaded', function () {
  const myPostsLink = document.getElementById('my_posts');
  const myFeedLink = document.getElementById('my_feed');
  const myFollowingLink = document.getElementById('my_following');

  const myPostsContainer = document.getElementById('my_posts_contents');
  const myFeedContainer = document.getElementById('feed');
  const followingContainer = document.getElementById('following');

  // Function to show the correct content based on the URL hash
  function showContent() {
      // Get the current hash from the URL
      const hash = window.location.hash;

      // Hide all content
      myPostsContainer.style.display = 'none';
      myFeedContainer.style.display = 'none';
      followingContainer.style.display = 'none';

      // Show the content based on the hash
      if (hash === '#my_posts') {
          myPostsContainer.style.display = 'inline-block';
      } else if (hash === '#feed') {
          myFeedContainer.style.display = 'inline-block';
      } else if (hash === '#following') {
          followingContainer.style.display = 'inline-block';
      }
  }

  // Event listeners for each link
  myPostsLink.addEventListener('click', function (e) {
      e.preventDefault(); // Prevent the default anchor action (page reload)
      window.location.hash = '#my_posts'; // Update the URL hash
      showContent(); // Show the content corresponding to the new hash
  });

  myFeedLink.addEventListener('click', function (e) {
      e.preventDefault(); // Prevent the default anchor action (page reload)
      window.location.hash = '#feed'; // Update the URL hash
      showContent(); // Show the content corresponding to the new hash
  });

  myFollowingLink.addEventListener('click', function (e) {
      e.preventDefault(); // Prevent the default anchor action (page reload)
      window.location.hash = '#following'; // Update the URL hash
      showContent(); // Show the content corresponding to the new hash
  });

  // Initially show content based on the current hash
  showContent();

  // Listen for changes in the URL hash
  window.addEventListener('hashchange', function () {
      showContent(); // Update content when hash changes
  });
});

















