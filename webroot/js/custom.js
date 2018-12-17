 // load fadeThis
$(document).ready(function() {
    $(window).fadeThis({
        speed: 500,
    });
});

// visible icon scrollTop
$(window).scroll(function () {
    var scrollAmount = $(window).scrollTop();
    if(scrollAmount > 300) {
        $('#toppage').addClass('moto-back-to-top-button_visible');
        $(".js-callit").show();
    } else {
        $('#toppage').removeClass('moto-back-to-top-button_visible');
        $(".js-callit").hide();
    }
}); 
// scrollTop 
var topBtn = $('#toppage');
topBtn.click(function () {
    $('body,html').animate({
        scrollTop: 0
    }, 500);
    return false;
});   
//menu sp 
$( "#menu-sp" ).on( "click", function() {
    if($(this).hasClass('active')) {
        $(this).removeClass("active");
        $(this).parent().removeClass("moto-widget-menu-mobile-open");

    }else{
        $(this).addClass( "active" );
        $(this).parent().addClass( "moto-widget-menu-mobile-open" );
    } 
});