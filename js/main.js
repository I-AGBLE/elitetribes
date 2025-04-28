setTimeout(() => {
  // Play sound
  // const sound = new Audio('../sounds/notification.mp3'); // Use a short .mp3 or .wav
  //sound.play();
  // Remove the alert
  alert_message.remove();
}, 10000);



  
  document.addEventListener("DOMContentLoaded", function () {
    const openFloatingInputBtn = document.querySelector('.open_floating_input');
    const closeFloatingInputBtn = document.querySelector('.close_floating_input');
    const postInput = document.querySelector('.floating_post_input');
    const mainContainer = document.querySelector('main');

    openFloatingInputBtn.addEventListener('click', function () {
      postInput.style.display = 'block';
      openFloatingInputBtn.style.display = 'none';
      closeFloatingInputBtn.style.display = 'block';
      mainContainer.style.opacity = '0.3'; // You can adjust this value if needed
    });

    closeFloatingInputBtn.addEventListener('click', function () {
      postInput.style.display = 'none';
      openFloatingInputBtn.style.display = 'block';
      closeFloatingInputBtn.style.display = 'none';
      mainContainer.style.opacity = '1';
    });
  });


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







