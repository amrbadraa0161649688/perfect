
// import "../styles.css";

let container = document.getElementById("image-container");
let top = container.offsetTop;
let left = container.offsetLeft;

function createIcon(color) {
    let icon = document.createElement("img");
    icon.src = `star_${color}.png`;
    icon.className = "star-icon";
    return icon;
}

container.addEventListener("click", function (e) {
    let icon = createIcon("red");
    let iconLeftOffset = icon.width / 2;
    let iconTopOffset = icon.height / 2;
    let mouseY = e.clientY;
    let mouseX = e.clientX;
    let yPosition = mouseY - top - iconTopOffset;
    let xPosition = mouseX - left - iconLeftOffset;
    icon.style.top = yPosition + "px";
    icon.style.left = xPosition + "px";
    container.appendChild(icon);
    //coordinates relative to div
    console.log("y", icon.offsetTop);
    console.log("x", icon.offsetLeft);
});