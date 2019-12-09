let open = true;
let col = (100 / 12) * 3;
let width = $(window).width();
$( '#arrow' ).click(function() {
    if (open == true) {
        $('#sidebar').css( 'left', '-' + col + '%' );
        $('#arrow i').css( 'transform', 'rotate(180deg)' );
        open = false;
    } else {
        $('#sidebar').css( 'left', '0' );
        $('#arrow i').css( 'transform', 'rotate(0deg)' );
        open = true;
    }
});

function hideOnResize() {
    if (width <= 1000) {
        $('#sidebar').css( 'left', '-' + col + '%' );
        $('#arrow i').css( 'transform', 'rotate(180deg)' );
        open = false;
    } else {
        $('#sidebar').css( 'left', '0' );
        $('#arrow i').css( 'transform', 'rotate(0deg)' );
        open = true;
    }
};

$(window).resize(function() {
    width = $(window).width();
    hideOnResize();
});