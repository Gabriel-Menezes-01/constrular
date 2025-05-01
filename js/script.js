let $navBar = $('.tod-cont');
$(document).on('scroll', function () {
    let scrollTop = $(window).scrollTop();
    if (scrollTop > 0) {
        $navBar.addClass('rolar');
    } else {
        $navBar.removeClass('rolar');
    }
});

// orçamento
$('.btn-resultado').on('click', function () {
    const $length = parseFloat($('#largura').val());
    const $width = parseFloat($('#comprimento').val());
    const $nome = $('#nome').val().trim();
    const $validar = $('.val');

    // Verifica se os campos estão preenchidos
    if (
        isNaN($length) ||
        isNaN($width) ||
        $nome === "" 
    ) {
        $validar.css('display', 'block');
        return;
    }

    // Faz o cálculo
    const area = $length * $width + 450;

    // Exibe a resposta
    const $resultDiv = $('.result');
    $resultDiv.css({ display: 'block' });
    $resultDiv.html(
        `Resultado:<br>Caro cliente ${$nome}, o valor do orçamento da construção fica em torno de ${area.toFixed(2)}€`
    );
});


// sobre
$('.gallery img').on('click', function() {
    $('.model').css('display', 'block');
    $('.model img').attr('src', $(this).attr('src'));
});

$('.close').on('click', function() {
    $('.model').css('display', 'none');
});


