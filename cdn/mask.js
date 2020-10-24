$(document).ready(function () {
    const BASE = $('link[rel="base"]').attr('href');
    // $( ".datepicker" ).datepicker({ dateFormat: 'dd/mm/yy' }).val();
    //############## DATEPICKER
    if ($('.jwc_datepicker').length || $('.jwc_datetimepicker').length) {
        $("head").append('<link rel="stylesheet" href="' + BASE + '/cdn/datepicker/datepicker.min.css">');
        $.getScript(BASE + '/cdn/datepicker/datepicker.min.js');
        $.getScript(BASE + '/cdn/datepicker/datepicker.pt-BR.js', function () {
            $('.jwc_datepicker').datepicker({language: 'pt-BR', autoClose: true});
            $('.jwc_datetimepicker').datepicker({language: 'pt-BR', autoClose: true, timepicker: true});
        });
    }
    $('.date').mask('00/00/0000');
    $('.time').mask('00:00:00');
    $('.timeHi').mask('00:00');
    $('.date_time').mask('00/00/0000 00:00:00');
    $('.cep').mask('00000-000');
    $('.phone').mask('0000-0000');
    $('.phone_with_ddd').mask('(00) 0000-00000');
    $('.phone_us').mask('(000) 000-0000');
    $('.mixed').mask('AAA 000-S0S');
    $('.cpf').mask('000.000.000-00', {reverse: true});
    $('.cnpj').mask('00.000.000/0000-00', {reverse: true});
    $('.money1').mask('000.000.000.000.000,00', {reverse: true});
    $('.money2').mask('000000000000000000000000.00', {reverse: true});
    $('.float').mask("#.##0,00", {reverse: true});
    $('.ip_address').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
        translation: {
            'Z': {
                pattern: /[0-9]/, optional: true
            }
        }
    });
    $('.ip_address').mask('099.099.099.099');
    $('.percent').mask('##0,00%', {reverse: true});
    $('.clear-if-not-match').mask("00/00/0000", {clearIfNotMatch: true});
    $('.placeholder').mask("00/00/0000", {placeholder: "__/__/____"});
    $('.fallback').mask("00r00r0000", {
        translation: {
            'r': {
                pattern: /[\/]/,
                fallback: '/'
            },
            placeholder: "__/__/____"
        }
    });
    $('.selectonfocus').mask("00/00/0000", {selectOnFocus: true});
});

var maskNonoDig = function (val) {
    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
}, addNonoDig = {
    onKeyPress: function (val, e, field, options) {
        field.mask(maskNonoDig.apply({}, arguments), options);
    }
};

$(document).ready(function () {
    $(".phone_with_ddd").mask(maskNonoDig, addNonoDig);
});
