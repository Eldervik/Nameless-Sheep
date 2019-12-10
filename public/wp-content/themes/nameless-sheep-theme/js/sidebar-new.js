let windowWidth = window.outerWidth;

let column = 100 / 3;
console.log("column: ", column);


console.log(windowWidth);
$(window).resize(function() {
    windowWidth = $(window).outerWidth();
    console.log(windowWidth);
});