let open = true;
let test = (100 / 12) * 3;
$( '#arrow' ).click(function() {
    if (open == true) {
        $('#sidebar').css( 'left', '-' + test + '%' );
        $('#arrow i').css( 'transform', 'rotate(180deg)' );
        open = false;
    } else {
        $('#sidebar').css( 'left', '0' );
        $('#arrow i').css( 'transform', 'rotate(0deg)' );
        open = true;
    }
});