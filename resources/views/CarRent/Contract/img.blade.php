<!DOCTYPE html>
<html>
<head>
    <title>Parcel Sandbox</title>
    <meta charset="UTF-8"/>

</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Cairo&display=swap');

    body {
        font-family: 'Cairo', sans-serif;
        margin-top: 100px;
        font-style: italic;
    }

    #image-container {
        width: 1000px;
        height: 500px;
        background-color: beige;
        position: relative;
        margin-left: auto;
        margin-right: auto;
    }

    .star-icon {
        position: absolute;
    }

    #container {
        height: 100%;
        width: 100%;
        font-size: 0;
        text-align: center;

    }

    #div1, #div2, #div3, #div4 {
        display: inline-block;
        *display: inline;
        zoom: 1;
        height: 65px;
        width: 15%;
        margin-right: 15px;
        margin-left: 15px;
        border: 3px #8a1f11 dashed;
        vertical-align: top;
        font-size: 10px;
        background: #faecda;

    }

    img, h2 {
        display: inline-block;
        vertical-align: middle;
        margin-top: 5px;
    }


</style>

<body>

<div id="container">
    <div id="div1">
        <img src="{{asset('images/star_darkRed.png')}}" onclick="setColor('darkRed')">
        <h2>إنحناء في الهيكل</h2>
    </div>
    <div id="div2">
        <img src="{{asset('images/star_red.png')}}" onclick="setColor('red')">
        <h2>خدش عميق جدا</h2>
    </div>
    <div id="div3">
        <img src="{{asset('images/star_blue.png')}}" onclick="setColor('blue')">
        <h2>خدش عميق</h2>
    </div>
    <div id="div4">
        <img src="{{asset('images/star_yellow.png')}}" onclick="setColor('yellow')">
        <h2>خدش بسيط</h2>
    </div>

    <form action="" method="post">
        <input type="hidden" name="top_col[]" id="top_col">
        <input type="hidden" name="left_col[]" id="left_col">
        <input type="hidden" name="col_key[]" id="col_key">

    </form>
</div>
<div id="app"></div>

<div id="image-container" style="background: url('{{ asset("images/car.png") }}') no-repeat center"></div>

<script>
    let container = document.getElementById("image-container");
    let top_a = container.offsetTop;
    let left_a = container.offsetLeft;

    let color = 'red';

    let top_col = [];
    let left_col = [];
    let col_key = [];

    function setColor(color_new) {
        color = color_new
    }


    function createIcon(color) {
        let icon = document.createElement("img");
        icon.src = '{{ asset("images") }}' + '/' + `star_${color}.png`;
        icon.className = "star-icon";
        return icon;
    }

    container.addEventListener("click", function (e) {
        let icon = createIcon(color);
        let iconLeftOffset = icon.width / 2;
        let iconTopOffset = icon.height / 2;
        let mouseY = e.clientY;
        let mouseX = e.clientX;
        let yPosition = mouseY - top_a - iconTopOffset;
        let xPosition = mouseX - left_a - iconLeftOffset;


        if (color == 'red') {
            top_col.push(yPosition)
            left_col.push(xPosition)
            col_key.push('very-deep-scratch')
        }
        else if (color == 'yellow') {
            top_col.push(yPosition)
            left_col.push(xPosition)
            col_key.push('small-scratch')
        }
        else if (color == 'darkRed') {
            top_col.push(yPosition)
            left_col.push(xPosition)
            col_key.push('bend-in-body')
        }
        // if (color == 'blue') {
        //     top_col.push({'bend-in-body': yPosition})
        //     left_col.push({'bend-in-body': xPosition})
        // }


        document.getElementById('top_col').value = top_col
        document.getElementById('left_col').value = left_col
        document.getElementById('col_key').value = col_key
        console.log(top_col)
        console.log(left_col)

        icon.style.top = yPosition + "px";
        icon.style.left = xPosition + "px";
        container.appendChild(icon);
        //coordinates relative to div
        console.log("y", icon.offsetTop);
        console.log("x", icon.offsetLeft);
    });
</script>

</body>
</html>