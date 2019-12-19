// Get the window width
let windowWidth = window.outerWidth;
// Get one column size in %
let columnWidth = 100 / 12;
let column = 3;
let menuOpen = true;
let hideWidth;

// Change the column width in %
function columnChange(column) {
    hideWidth = columnWidth * column + "%";
}

// Run this function if window is resized
function hideOnResize(windowWidth) {
    // Stretch the content (from 9 to 12 column)
    $('.content').removeClass( 'col-12' );
    $('.content').addClass( 'col-9' );
    // Hide the sidebar to the left and rotate the arrow icon
    $('#sidebar').css( 'left', '0' );
    $('#arrow i').css( 'transform', 'rotate(0deg)' );
    menuOpen = true;
    
    // Check the size of the window
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

// Hide the sidebar on window resize
hideOnResize(windowWidth);
$(window).resize(function() {
    windowWidth = $(window).outerWidth();
    hideOnResize(windowWidth);
});

// Hide / Show the sidebar onclick
$( '#arrow' ).click(function() {
    // Check if the sidebar is open or closed
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
