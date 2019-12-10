let open = true;

// small 540px
// medium 720px
// large 960px

// col-lg-3 col-md-4 col-sm-6 col-11

let col = (100 / 12) * 3;
let width = $(window).outerWidth();
$( '#arrow' ).click(function() {
    if (open == true) {
        $('#sidebar').css( 'left', '-' + col + '%' );
        $('.content').removeClass( 'col-9' );
        $('.content').addClass( 'col-12' );
        $('#arrow i').css( 'transform', 'rotate(180deg)' );
        open = false;
    } else {
        $('.content').removeClass( 'col-12' );
        $('.content').addClass( 'col-9' );
        $('#sidebar').css( 'left', '0' );
        $('#arrow i').css( 'transform', 'rotate(0deg)' );
        open = true;
    }
});

function hideOnResize() {
    if (width <= 960) {
        $('#sidebar').css( 'left', '-' + col + '%' );
        $('.content').removeClass( 'col-9' );
        $('.content').addClass( 'col-12' );
        $('#arrow i').css( 'transform', 'rotate(180deg)' );
        open = false;
    } else {
        $('#sidebar').css( 'left', '0' );
        $('.content').addClass( 'col-9' );
        $('#sidebar').css( 'left', '0' );
        $('#arrow i').css( 'transform', 'rotate(0deg)' );
        open = true;
    }
};

$(window).resize(function() {
    width = $(window).outerWidth();
    hideOnResize();
});