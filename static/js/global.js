$('.money').mask('000.000.000.000.000,00', { reverse: true });
moment.locale('pt-BR');

var mesCurto = ["", "Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"];

/*
 * 
 * Definições da Storage
 */
const lsDefault = "contaVinculada";
var contaVinculada = {};

var iniciaVariaveis = function () {
    contaVinculada = {
        "empresa": {
            "id": "",
            "nome": "Todas"
        },
        "empregado": {
            "id": "",
            "nome": "Todos"
        },
        "contrato": {
            "id": "",
            "nu_contrato": "Todos"
        }
    };
    ls.save(contaVinculada);
};

var msg = {
    "OK": "Ok",
    "ERRO": "Erro",
    "ERRO_DETALHE": "(@@erro)",
    "SUCCESS": "success",
    "INFO": "info",
    "WARNING": "warning",
    "DANGER": "danger",
    "ICO_EXCLAMATION": "fa fa-exclamation-triangle fa-2x",
    "ICO_CHECK": "fa fa-check-circle fa-2x",
    "ICO_REFRESH": "fa fa-refresh fa-spin fa-fw fa-2x",
    "COM_ESPERA": 5000,
    "SEM_ESPERA": 0,
    "CAMPO_OBRIGATORIO": "Campo Obrigatório",
    "PESQUISANDO": "Pesquisando @@assunto",
    "INCLUIDO": "incluído",
    "ALTERADO": "alterado",
    "EXCLUIDO": "excluído",
    "REMOVIDO": "removido",
    "MENSAGEM_SUCESSO": "@@assunto @@acao com sucesso"
};

cv = {
    "id": "",
    "extras": {
        "voltou": false,
        "etapa": "",
        "primeira": true
    }
};

var notify, progress;


/*
 * Manipula informações da storage
 */
var ls = {
    name: lsDefault,
    json: contaVinculada,
    save: function (o, n) {
        var name = (typeof n === 'undefined') ? this.name : n;
        if (!$.isEmptyObject(o)) {
            //o:object json
            this.json = o;
            localStorage.setItem(name, JSON.stringify(this.json));
        }
        return this.json;
    },
    load: function (n) {
        var name = (typeof n === 'undefined') ? this.name : n;
        if ((typeof n === 'undefined')) {
            if (!$.isEmptyObject(localStorage.getItem(name))) {
                this.json = toJson(localStorage.getItem(name));
            }
        } else {
            if (localStorage.getItem(n)) {
                this.json = toJson(localStorage.getItem(n));
            } else {
                this.json = null;
            }
        }
        return this.json;
    },
    clear: function () {
        localStorage.clear();
    },
    remove: function (n) {
        var name = (typeof n === 'undefined') ? this.name : n;
        localStorage.removeItem(name);
    },
    call: function () {
    }
};

//var mensagem = {
//    titulo: '',
//    texto: '',
//    permitirFechar: true,
//    tipo: msg.SUCCESS,
//    icone: msg.ICO_REFRESH,
//    url: '',
//    destino: '_self',
//    posicao: 'top',
//    espera: msg.COM_ESPERA,
//    barraDeProgresso: false,
//    progresso: 0,
//
//    exibe: function () {
//
//        //console.log('exibe');
//
//        notify = $.notify({
//            // options
//            title: '<strong>' + this.titulo + '</strong>',
//            message: this.texto,
//            icon: this.icone,
//            url: this.url,
//            target: this.destino
//        }, {
//            // settings
//            type: this.tipo,
//            showProgressbar: this.barraDeProgresso,
//            progress: this.progresso,
//            allow_dismiss: this.permitirFechar,
//            delay: this.espera,
//            placement: {
//                from: this.posicao,
//                align: 'center'
//            },
//            offset: {
//                x: 50,
//                y: 44
//            },
//            animate: {
//                enter: 'animated fadeIn',
//                exit: 'animated fadeOut'
//            }
//        });
//
//        this.call();
//
//    },
//    atualiza: function () {
//
//        //console.log('atualiza');
//
//        if ($("[class*='alert-']").length > 2) {
//            notify.update({
//                title: '<strong>' + this.titulo + '</strong>',
//                message: this.texto,
//                icon: this.icone,
//                url: this.url,
//                target: this.destino,
//                progress: this.progresso
//            }, {
//                type: this.tipo
//            });
//        } else {
//            mensagem.exibe();
//        }
//        this.call();
//
//    },
//    esconde: function () {
//        notify.close();
//        //console.log('esconde');
//        this.call();
//    },
//    escondeTodas: function () {
//        $.notifyClose();
//        //console.log('escondeTodas');
//    },
//    call: function () {
//        //console.log(this);
//        mensagem.titulo = '';
//        mensagem.texto = '';
//    }
//};

/**
 * Recurso para mensagens no sistema
 */
var mensagem = {
    titulo: '',
    texto: '',
    permitirFechar: true,
    tipo: msg.SUCCESS,
    icone: msg.ICO_REFRESH,
    url: '',
    destino: '_self',
    posicao: 'top',
    espera: msg.COM_ESPERA,
    barraDeProgresso: false,
    progresso: 0,

    exibe: function () {

        //console.log('exibe');

        notify = $.notify({
            // options
            title: '<span class="notify-title">' + this.titulo + '</span>',
            message: this.texto,
            icon: this.icone,
            url: this.url,
            target: this.destino
        }, {
            // settings
            type: this.tipo,
            z_index: 2000,
            showProgressbar: this.barraDeProgresso,
            progress: this.progresso,
            allow_dismiss: this.permitirFechar,
            delay: this.espera,
            placement: {
                from: this.posicao,
                align: 'center'
            },
            offset: {
                x: 50,
                y: 44
            },
            animate: {
                enter: 'animated fadeIn',
                exit: 'animated fadeOut'
            },
            onShow:
                //console.log('notify - onShow')
                $.blockUI,
            onShown: null,
            onClose:
                //console.log('notify - onClose')
                $.unblockUI,
            onClosed: null
        });

        this.call();

    },
    atualiza: function () {

        //console.log('atualiza');

        if (($("[class*='alert-']").length > 2) && ($("[data-notify*='container']").length > 0)) {
            notify.update({
                title: '<span class="notify-title">' + this.titulo + '</span>',
                message: this.texto,
                icon: this.icone,
                url: this.url,
                target: this.destino,
                progress: this.progresso
            }, {
                // settings
                type: this.tipo,
                z_index: 2000,
                onShow: /*$.blockUI*/ null,
                onShown: null,
                onClose: /*$.unblockUI*/ null,
                onClosed: null
            });
        } else {
            mensagem.exibe();
        }
        this.call();

    },
    esconde: function () {
        notify.close();
        //console.log('esconde');
        this.call();
    },
    escondeTodas: function () {
        $.notifyClose();
        //console.log('escondeTodas');
    },
    call: function () {
        //console.log(this);
        mensagem.titulo = '';
        mensagem.texto = '';
    }
};

$.blockUI.defaults = {
    // message displayed when blocking (use null for no message)
    //message: '<h1>Please wait...</h1>',
    message: null,

    title: null, // title string; only used when theme == true
    draggable: true, // only used when theme == true (requires jquery-ui.js to be loaded)

    theme: false, // set to true to use with jQuery UI themes

    // styles for the message when blocking; if you wish to disable
    // these and use an external stylesheet then do this in your code:
    // $.blockUI.defaults.css = {};
    css: {
        padding: 0,
        margin: 0,
        width: '30%',
        top: '40%',
        left: '35%',
        textAlign: 'center',
        color: '#000',
        border: '3px solid #aaa',
        backgroundColor: '#fff',
        cursor: 'wait'
    },

    // minimal style set used when themes are used
    themedCSS: {
        width: '30%',
        top: '40%',
        left: '35%'
    },

    // styles for the overlay
    overlayCSS: {
        //backgroundColor: '#fff',
        backgroundColor: '#000',
        opacity: 0.6,
        cursor: 'wait'
    },

    // style to replace wait cursor before unblocking to correct issue
    // of lingering wait cursor
    cursorReset: 'default',

    // styles applied when using $.growlUI
    growlCSS: {
        width: '350px',
        top: '10px',
        left: '',
        right: '10px',
        border: 'none',
        padding: '5px',
        opacity: 0.6,
        cursor: null,
        color: '#fff',
        backgroundColor: '#000',
        '-webkit-border-radius': '10px',
        '-moz-border-radius': '10px'
    },

    // IE issues: 'about:blank' fails on HTTPS and javascript:false is s-l-o-w
    // (hat tip to Jorge H. N. de Vasconcelos)
    iframeSrc: /^https/i.test(window.location.href || '') ? 'javascript:false' : 'about:blank',

    // force usage of iframe in non-IE browsers (handy for blocking applets)
    forceIframe: false,

    // z-index for the blocking overlay
    //baseZ: 1000,
    baseZ: 1999,

    // set these to true to have the message automatically centered
    centerX: true, // <-- only effects element blocking (page block controlled via css above)
    centerY: true,

    // allow body element to be stetched in ie6; this makes blocking look better
    // on "short" pages.  disable if you wish to prevent changes to the body height
    allowBodyStretch: true,

    // enable if you want key and mouse events to be disabled for content that is blocked
    bindEvents: true,

    // be default blockUI will supress tab navigation from leaving blocking content
    // (if bindEvents is true)
    constrainTabKey: true,

    // fadeIn time in millis; set to 0 to disable fadeIn on block
    //fadeIn: 200,
    fadeIn: 0,

    // fadeOut time in millis; set to 0 to disable fadeOut on unblock
    //fadeOut: 400,
    fadeOut: 0,

    // time in millis to wait before auto-unblocking; set to 0 to disable auto-unblock
    timeout: 0,

    // disable if you don't want to show the overlay
    showOverlay: true,

    // if true, focus will be placed in the first available input field when
    // page blocking
    focusInput: true,

    // suppresses the use of overlay styles on FF/Linux (due to performance issues with opacity)
    // no longer needed in 2012
    // applyPlatformOpacityRules: true,

    // callback method invoked when fadeIn has completed and blocking message is visible
    onBlock: null,

    // callback method invoked when unblocking has completed; the callback is
    // passed the element that has been unblocked (which is the window object for page
    // blocks) and the options that were passed to the unblock call:
    //   onUnblock(element, options)
    onUnblock: null,

    // don't ask; if you really must know: http://groups.google.com/group/jquery-en/browse_thread/thread/36640a8730503595/2f6a79a77a78e493#2f6a79a77a78e493
    quirksmodeOffsetHack: 4,

    // class name of the message block
    blockMsgClass: 'blockMsg',

    // if it is already blocked, then ignore it (don't unblock and reblock)
    ignoreIfBlocked: false
};

var isEmpty = function (data) {
    if (typeof (data) === 'object') {
        if (JSON.stringify(data) === '{}' || JSON.stringify(data) === '[]') {
            return true;
        } else if (!data) {
            return true;
        }
        return false;
    } else if (typeof (data) === 'string') {
        if (!data.trim()) {
            return true;
        }
        return false;
    } else if (typeof (data) === 'undefined') {
        return true;
    } else {
        return false;
    }
};

var setStringEmpty = function (v) {
    return (typeof v === 'undefined') ? "" : v;
};

var setArrayEmpty = function (a) {
    return (typeof a === 'undefined') ? new Array() : a;
};

//var tratarCampoLupa = function (f) {
//    //console.assert('tratarCampoLupa');
//    var elementMessage, styleInputMessage, styleMessage, required;
//    var styleInputError = "is-invalid";
//    var styleMessageError = "invalid-feedback";
//    var styleInputOk = "is-valid";
//    var styleMessageOk = "valid-feedback";
//    var campo_erro = $(f.id).parent().next();
//    try {
//        if ((f.id) && (f.id !== null) && (f.id !== '')) {
//            elementMessage = $(f.id).next();
//            if (f.erro) {
//                required = true;
//                styleInputMessage = styleInputError;
//                styleMessage = styleMessageError;
//                $(f.id).addClass('is-invalid');
//                $(f.id).addClass('borda-erro');
//                if ($(campo_erro).attr("class").indexOf('invalid') !== -1) {
//                    $(campo_erro).show();
//                    $(campo_erro).html(f.message);
//                }
//            } else {
//                required = false;
//                styleInputMessage = styleInputOk;
//                styleMessage = styleMessageOk + " hidden";
//                $(f.id).removeClass(styleInputError);
//                $(f.id).removeClass('borda-erro');
//                // $(elementMessage).removeClass(styleMessageError);
//                if ($(campo_erro).attr("class").indexOf('invalid') !== -1) {
//                    $(campo_erro).hide();
//                }
//            }
//            $(f.id).addClass(styleInputMessage);
//            $(f.id).trigger("change");
//
//        } else {
//            throw 'Nao foram passados todos os argumentos';
//        }
//    } catch (e) {//console.log(e);
//    }
//};

var tratarCampoLupa = function (f) {
    var elementMessage, styleInputMessage, styleMessage, required;
    var styleInputError = "is-invalid";
    var styleMessageError = "invalid-feedback";
    var styleInputOk = "is-valid";
    var styleMessageOk = "valid-feedback";
    var campo_erro = $(f.id).parent().next();
    try {
        if (f.id !== null && f.id !== '') {
            elementMessage = $(f.id).next();
            if (f.erro) {
                required = true;
                styleInputMessage = styleInputError;
                styleMessage = styleMessageError;
                $(campo_erro).show();
                //$(campo_erro).html(f.message);
            } else {
                required = false;
                styleInputMessage = styleInputOk;
                styleMessage = styleMessageOk + " hidden";
                $(f.id).removeClass(styleInputError);
                $(elementMessage).removeClass(styleMessageError);
                //$(campo_erro).hide();
            }
            $(f.id).addClass(styleInputMessage);
            $(f.id).trigger("change");

        } else {
            throw 'Nao foram passados todos os argumentos';
        }
    } catch (e) {//console.log(e);
    }

};

var tratarCampo = function (f) {
    var elementMessage, styleInputMessage, styleMessage, required;
    var styleInputError = "is-invalid";
    var styleMessageError = "invalid-feedback";
    var styleInputOk = "is-valid";
    var styleMessageOk = "valid-feedback";
    try {
        // console.log(f);
        if (f.id != null && f.id != '') {

            elementMessage = $(f.id).next();
            if (f.erro) {
                required = true;
                styleInputMessage = styleInputError;
                styleMessage = styleMessageError;
            } else {
                required = false;
                styleInputMessage = styleInputOk;
                styleMessage = styleMessageOk + " hidden";
                $(f.id).removeClass(styleInputError);
                $(elementMessage).removeClass(styleMessageError);
            }

            $(f.id).attr('required', required);
            $(elementMessage).html(f.message).addClass(styleMessage);
            $(f.id).addClass(styleInputMessage);
            $(f.id).trigger("change");

            /*
             console.log(required);
             console.log(elementMessage);
             console.log(styleInputMessage);
             console.log(styleMessage);
             */

        } else {
            throw 'Nao foram passados todos os argumentos';
        }
    } catch (e) {//console.log(e);
    }

};

var toJson = function (x) {

    var json = {};

    if (x !== '' && x !== null) {

        try {
            var json = JSON.parse(x);
        } catch (e) {
            json = x;
        }

    }

    return json;

};

$(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();

    //Definições gerais do dataTable
    $.extend(true, $.fn.dataTable.defaults, {
        "searching": true,
        "ordering": true,
        fixedHeader: true,
        "scrollCollapse": true,
        "paging": true,
        responsive: true,
        destroy: true,
        //"order": [[2, "asc"]],
        "oLanguage": {
            "sEmptyTable": "Nenhum registro encontrado",
            "sProcessing": "Processando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "Não foram encontrados resultados",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando de 0 até 0 de 0 registros",
            "sInfoFiltered": "",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "Primeiro",
                "sPrevious": "Anterior",
                "sNext": "Seguinte",
                "sLast": "Último"
            }
        }
    });
});

$(document).ready(function () {

    $('[data-toggle=confirmation]').confirmation({
        rootSelector: '[data-toggle=confirmation]',
        btnOkLabel: "&nbsp;Sim",
        btnCancelLabel: "&nbsp;Não",
        popout: true,
        singleton: true,
        placement: 'right',
        title: 'Deseja remover este item?'
    });

    $('[data-toggle="tooltip"]').tooltip();
    $("[data-tt=tooltip]").tooltip();
    $('[data-toggle="popover"]').popover();
    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });
    $('body').popover({
        selector: '[data-toggle="popover"]'
    });

    $('.money').mask('000.000.000,00', {
        reverse: true
    });

});

$('#custom-confirmation-links').confirmation({
    rootSelector: '#custom-confirmation-link',
    container: 'body',
    title: '',
    buttons: [{
        label: 'Twitter',
        iconClass: 'fa fa-twitter fa-lg mr-1',
        attr: {
            href: 'https://twitter.com',
            target: '_blank'
        }
    }, {
        label: 'Facebook',
        iconClass: 'fa fa-facebook fa-lg mr-1',
        attr: {
            href: 'https://facebook.com',
            target: '_blank'
        }
    }, {
        label: 'Pinterest',
        iconClass: 'fa fa-pinterest fa-lg mr-1',
        attr: {
            href: 'https://pinterest.com',
            target: '_blank'
        }
    }]
});

function verifica_cpf_cnpj(valor) {

    // Garante que o valor é uma string
    valor = valor.toString();

    // Remove caracteres inválidos do valor
    valor = valor.replace(/[^0-9]/g, '');

    // Verifica CPF
    if (valor.length === 11) {
        return 'CPF';
    }
    // Verifica CNPJ
    else if (valor.length === 14) {
        return 'CNPJ';
    }
    // Não retorna nada
    else {
        return false;
    }

}

// verifica_cpf_cnpj

/*
 calc_digitos_posicoes
 
 Multiplica dígitos vezes posições
 
 @param string digitos Os digitos desejados
 @param string posicoes A posição que vai iniciar a regressão
 @param string soma_digitos A soma das multiplicações entre posições e dígitos
 @return string Os dígitos enviados concatenados com o último dígito
 */
function calc_digitos_posicoes(digitos, posicoes = 10, soma_digitos = 0) {

    // Garante que o valor é uma string
    digitos = digitos.toString();

    // Faz a soma dos dígitos com a posição
    // Ex. para 10 posições:
    //   0    2    5    4    6    2    8    8   4
    // x10   x9   x8   x7   x6   x5   x4   x3  x2
    //   0 + 18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
    for (var i = 0; i < digitos.length; i++) {
        // Preenche a soma com o dígito vezes a posição
        soma_digitos = soma_digitos + (digitos[i] * posicoes);

        // Subtrai 1 da posição
        posicoes--;

        // Parte específica para CNPJ
        // Ex.: 5-4-3-2-9-8-7-6-5-4-3-2
        if (posicoes < 2) {
            // Retorno a posição para 9
            posicoes = 9;
        }
    }

    // Captura o resto da divisão entre soma_digitos dividido por 11
    // Ex.: 196 % 11 = 9
    soma_digitos = soma_digitos % 11;

    // Verifica se soma_digitos é menor que 2
    if (soma_digitos < 2) {
        // soma_digitos agora será zero
        soma_digitos = 0;
    } else {
        // Se for maior que 2, o resultado é 11 menos soma_digitos
        // Ex.: 11 - 9 = 2
        // Nosso dígito procurado é 2
        soma_digitos = 11 - soma_digitos;
    }

    // Concatena mais um dígito aos primeiro nove dígitos
    // Ex.: 025462884 + 2 = 0254628842
    var cpf = digitos + soma_digitos;

    // Retorna
    return cpf;

}

// calc_digitos_posicoes

/*
 Valida CPF
 
 Valida se for CPF
 
 @param  string cpf O CPF com ou sem pontos e traço
 @return bool True para CPF correto - False para CPF incorreto
 */
function valida_cpf(valor) {

    // Garante que o valor é uma string
    valor = valor.toString();

    // Remove caracteres inválidos do valor
    valor = valor.replace(/[^0-9]/g, '');

    if (valor.length !== 11 || valor === "00000000000" || valor === "11111111111" ||
        valor === "22222222222" || valor === "33333333333" ||
        valor === "44444444444" || valor === "55555555555" ||
        valor === "66666666666" || valor === "77777777777" ||
        valor === "88888888888" || valor === "99999999999")
        return false;

    // Captura os 9 primeiros dígitos do CPF
    // Ex.: 02546288423 = 025462884
    var digitos = valor.substr(0, 9);

    // Faz o cálculo dos 9 primeiros dígitos do CPF para obter o primeiro dígito
    var novo_cpf = calc_digitos_posicoes(digitos);

    // Faz o cálculo dos 10 dígitos do CPF para obter o último dígito
    var novo_cpf = calc_digitos_posicoes(novo_cpf, 11);

    // Verifica se o novo CPF gerado é idêntico ao CPF enviado
    if (novo_cpf === valor) {
        // CPF válido
        return true;
    } else {
        // CPF inválido
        return false;
    }

}

// valida_cpf

/*
 valida_cnpj
 
 Valida se for um CNPJ
 
 @param string cnpj
 @return bool true para CNPJ correto
 */
function valida_cnpj(valor) {

    // Garante que o valor é uma string
    valor = valor.toString();

    // Remove caracteres inválidos do valor
    valor = valor.replace(/[^0-9]/g, '');

    // O valor original
    var cnpj_original = valor;

    // Captura os primeiros 12 números do CNPJ
    var primeiros_numeros_cnpj = valor.substr(0, 12);

    // Faz o primeiro cálculo
    var primeiro_calculo = calc_digitos_posicoes(primeiros_numeros_cnpj, 5);

    // O segundo cálculo é a mesma coisa do primeiro, porém, começa na posição 6
    var segundo_calculo = calc_digitos_posicoes(primeiro_calculo, 6);

    // Concatena o segundo dígito ao CNPJ
    var cnpj = segundo_calculo;

    // Verifica se o CNPJ gerado é idêntico ao enviado
    if (cnpj === cnpj_original) {
        return true;
    }

    // Retorna falso por padrão
    return false;

}

// valida_cnpj

/*
 valida_cpf_cnpj
 
 Valida o CPF ou CNPJ
 
 @access public
 @return bool true para válido, false para inválido
 */
function valida_cpf_cnpj(valor) {

    // Verifica se é CPF ou CNPJ
    var valida = verifica_cpf_cnpj(valor);

    // Garante que o valor é uma string
    valor = valor.toString();

    // Remove caracteres inválidos do valor
    valor = valor.replace(/[^0-9]/g, '');

    // Valida CPF
    if (valida === 'CPF') {
        // Retorna true para cpf válido
        return valida_cpf(valor);
    }
    // Valida CNPJ
    else if (valida === 'CNPJ') {
        // Retorna true para CNPJ válido
        return valida_cnpj(valor);
    }
    // Não retorna nada
    else {
        return false;
    }

}

// valida_cpf_cnpj

/*
 formata_cpf_cnpj
 
 Formata um CPF ou CNPJ
 
 @access public
 @return string CPF ou CNPJ formatado
 */
function formata_cpf_cnpj(valor) {

    // O valor formatado
    var formatado = false;

    // Verifica se é CPF ou CNPJ
    var valida = verifica_cpf_cnpj(valor);

    // Garante que o valor é uma string
    valor = valor.toString();

    // Remove caracteres inválidos do valor
    valor = valor.replace(/[^0-9]/g, '');

    // Valida CPF
    if (valida === 'CPF') {

        // Verifica se o CPF é válido
        if (valida_cpf(valor)) {

            // Formata o CPF ###.###.###-##
            formatado = valor.substr(0, 3) + '.';
            formatado += valor.substr(3, 3) + '.';
            formatado += valor.substr(6, 3) + '-';
            formatado += valor.substr(9, 2) + '';

        }

    }
    // Valida CNPJ
    else if (valida === 'CNPJ') {

        // Verifica se o CNPJ é válido
        if (valida_cnpj(valor)) {

            // Formata o CNPJ ##.###.###/####-##
            formatado = valor.substr(0, 2) + '.';
            formatado += valor.substr(2, 3) + '.';
            formatado += valor.substr(5, 3) + '/';
            formatado += valor.substr(8, 4) + '-';
            formatado += valor.substr(12, 14) + '';

        }

    }

    // Retorna o valor
    return formatado;

}

// formata_cpf_cnpj

var aplicaMascara = function (campo, mascara) {
    if (campo != null) {
        $(campo).unmask().mask(mascara);
    }
};

function maiuscula(z) {
    v = z.value.toUpperCase();
    z.value = v;
}

var temporizador = function (url, tempo) {
    var t = isEmpty(tempo) ? totalSegundos : tempo;
    try {
        $(".temporizador").html(segs);
        segs--;
        if (segs < 0) {
            segs = t;
            // location.href = url;
        }
        console.log(segs);
    } catch (err) {
        $(".temporizador").html(err.message);
    }
};

$(function () {
    $('.date-picker').datepicker({
        format: "mm/yyyy",
        language: "pt-BR",
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'MM yy',
        onClose: function (dateText, inst) {
            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
        }
    });
});

$("input[class*='data']").datepicker({
    format: "dd/mm/yyyy",
    language: "pt-BR",
    // clearBtn: true,
    // showWeekDays: true,
    defaultViewDate: true,
    todayBtn: true,
    todayHighlight: true
    // title: "Selecione um,a Data"
});

String.prototype.formatMoney = function () {
    return (
        this
            .replace('.', ',') //Troca o ponto decimal pela vírgula
            .replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
    )
}

String.prototype.formataData = function () {
    var dateFormated = this;
    if (dateFormated == '0000-00-00' || dateFormated == undefined)
        return '0000-00-00';

    if (this.indexOf('-') != -1)
        var data = dateFormated.split("-");

    if (this.indexOf('/') != -1)
        var data = dateFormated.split("/");

    return data[2] + "/" + data[1] + "/" + data[0];
};

function calculaDias(date1, date2) {
    //formato do brasil 'pt-br'
    moment.locale('pt-br');
    //setando data1
    var data1 = moment(date1, 'DD/MM/YYYY');
    //setando data2
    var data2 = moment(date2, 'DD/MM/YYYY');
    //tirando a diferenca da data2 - data1 em dias
    var diff = data2.diff(data1, 'days');
    return diff;
}

function registraLogAcesso(_url) {
    var dados = {};
    var data, status, xhr;

    $.post(APP_HTTP + 'controle_acesso/log_acesso/logacesso.php', { acao: 'registrarLogAcesso', url: _url }, function (data, status, xhr) {
        dados = toJson(data);

    }).success(function () {
        if (dados.erro === true) {
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 2000;
            mensagem.exibe();
            return false;
        } else {
            return true;
        }
    }).error(function (xhr) {
        mensagem.titulo = dados.mensagem;
        mensagem.espera = 5000;
        mensagem.exibe();
        return false;
    });
}

function validaEmail(email) {
    var reg = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
    if (reg.test(email)) {
        return true;
    } else {
        return false;
    }
}

/*
 * Manipula informações da storage
 */
var ls = {
    name: lsDefault,
    json: contaVinculada,
    save: function (o, n) {
        var name = (typeof n === 'undefined') ? this.name : n;
        if (!$.isEmptyObject(o)) {
            //o:object json
            this.json = o;
            localStorage.setItem(name, JSON.stringify(this.json));
        }
        return this.json;
    },
    load: function (n) {
        var name = (typeof n === 'undefined') ? this.name : n;
        if ((typeof n === 'undefined')) {
            if (!$.isEmptyObject(localStorage.getItem(name))) {
                this.json = toJson(localStorage.getItem(name));
            }
        } else {
            if (localStorage.getItem(n)) {
                this.json = toJson(localStorage.getItem(n));
            } else {
                this.json = null;
            }
        }
        return this.json;
    },
    clear: function () {
        localStorage.clear();
    },
    remove: function (n) {
        var name = (typeof n === 'undefined') ? this.name : n;
        localStorage.removeItem(name);
    },
    call: function () {
    }
};

// Função sleep baseada em micro segundos.
const sleep = (milliseconds) => {
    console.log(`Sleeping...(${milliseconds})`);
    return new Promise(resolve => setTimeout(resolve, milliseconds));
};

// Mostra a tela de carregando
const carregando = (flag) => {
    if (flag === 'on') {
        mensagem.tipo = msg.SUCCESS;
        mensagem.titulo = 'Carregando';
        mensagem.exibe();           
    } else {
        mensagem.esconde();           
    }
    return true;
}