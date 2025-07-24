let $navBar = $('.tod-cont');
$(document).on('scroll', function () {
    let scrollTop = $(window).scrollTop();
    if (scrollTop > 0) {
        $navBar.addClass('rolar');
    } else {
        $navBar.removeClass('rolar');
    }
});




// sobre
$('.gallery img').on('click', function() {
    $('.model').css('display', 'block');
    $('.model img').attr('src', $(this).attr('src'));
});

$('.close').on('click', function() {
    $('.model').css('display', 'none');
});


