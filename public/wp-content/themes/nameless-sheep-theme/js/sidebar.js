let windowWidth = window.outerWidth;
let columnWidth = 100 / 12;
let column = 3;
let menuOpen = true;
let hideWidth;

function hideOnResize(windowWidth) {
    $('.content').removeClass( 'col-12' );
    $('.content').addClass( 'col-9' );
    $('#sidebar').css( 'left', '0' );
    $('#arrow i').css( 'transform', 'rotate(0deg)' );
    menuOpen = true;
    
    // columnWidth * column;
    if (windowWidth <= 540) {
        column = 11;
        menuOpen = false;
    } else if (windowWidth <= 720) {
        column = 6;
        menuOpen = false;
    } else if (windowWidth <= 940) {
        column = 4;
    }
    columnChange(column);
};

function columnChange(column) {
    hideWidth = columnWidth * column + "%";
}

hideOnResize(windowWidth);
$(window).resize(function() {
    windowWidth = $(window).outerWidth();
    hideOnResize(windowWidth);
});

$( '#arrow' ).click(function() {
    if (menuOpen) {
        $('#sidebar').css( 'left', '-' + hideWidth );
        $('.content').removeClass( 'col-9' );
        $('.content').addClass( 'col-12' );
        $('#arrow i').css( 'transform', 'rotate(180deg)' );
        menuOpen = false;
    } else {
        $('.content').removeClass( 'col-12' );
        $('.content').addClass( 'col-9' );
        $('#sidebar').css( 'left', '0' );
        $('#arrow i').css( 'transform', 'rotate(0deg)' );
        menuOpen = true;
    }
});
