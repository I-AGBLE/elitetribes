// Title Bar Text Animation
const defaultTitleText = "From Void To Signals ...";
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
















