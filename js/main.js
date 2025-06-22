setTimeout(() => {
  alert_message.remove();
}, 10000);

document.addEventListener("DOMContentLoaded", function () {
  const openFloatingInputBtn = document.querySelector(".open_floating_input");
  const closeFloatingInputBtn = document.querySelector(".close_floating_input");
  const postInput = document.querySelector(".floating_post_input");
  const mainContainer = document.querySelector("main");

  openFloatingInputBtn.addEventListener("click", function () {
    postInput.style.display = "block";
    openFloatingInputBtn.style.display = "none";
    closeFloatingInputBtn.style.display = "block";
    mainContainer.style.opacity = "0.3"; // You can adjust this value if needed
  });

  closeFloatingInputBtn.addEventListener("click", function () {
    postInput.style.display = "none";
    openFloatingInputBtn.style.display = "block";
    closeFloatingInputBtn.style.display = "none";
    mainContainer.style.opacity = "1";
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
