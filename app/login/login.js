$('.email').focus()

$(document).on('click', '.botao-site', function () { 
    location.href = '../../site/#contact';
});

$('.form').find('input, textarea').on('keyup blur focus', function (e) {
    var $this = $(this), label = $this.prev('label');

    if (e.type === 'keyup') {
        if ($this.val() === '') {
            label.removeClass('active highlight');
        } else {
            label.addClass('active highlight');
        }
    } else if (e.type === 'blur') {
        if ($this.val() === '') {
            label.removeClass('active highlight');
        } else {
            label.removeClass('highlight');
        }
    } else if (e.type === 'focus') {

        if ($this.val() === '') {
            label.removeClass('highlight');
        } else if ($this.val() !== '') {
            label.addClass('highlight');
        }
    }
});

$('.tab a').on('click', function (e) {
    e.preventDefault();

    $(this).parent().addClass('active');
    $(this).parent().siblings().removeClass('active');

    target = $(this).attr('href');
    $('.tab-content > div').not(target).hide();

    $(target).fadeIn(600);

});

$(document).keypress(function (e) {
    if (e.which === 13)
        $('.entrar').click();
});

$('.entrar').click(function (e) {
    if (!isEmpty($('.email').val()) && !isEmpty($('.senha').val())) {

        var parametros = {
            acao: 'logar',
            senha: $('.senha').val(),
            email: $('.email').val()
        };

        var dados = {};
        var data, status, xhr;

        $.post('login.php', parametros, function (data, status, xhr) {
            dados = toJson(data);
        }).success(function () {
            if (dados.erro === true) {
                mensagem.titulo = dados.mensagem;
                mensagem.espera = 3000;
                mensagem.exibe();
                return false;
            } else if (isEmpty(dados)) {
                mensagem.titulo = 'Problemas na conexão com o banco. Verifique ou tente mais tarde.';
                mensagem.espera = 3000;
                mensagem.exibe();
                return false;
            } else if (dados.erro === false) {
                contaVinculada = ls.load()

                if(typeof contaVinculada.empresa === "undefined"){
                    iniciaVariaveis();
                }

                ls.save(contaVinculada);

                registraLogAcesso(URL_ATUAL);
                location.href = APP_HTTP + 'index.php';

            } else {
                mensagem.titulo = 'Problemas no sistema. Procure o administrador ou tente mais tarde.';
                mensagem.espera = 3000;
                mensagem.exibe();
                return false;
            }
        }).error(function (xhr) {
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 3000;
            mensagem.exibe();
            return false;
        });
    } else {
        mensagem.titulo = 'Campos de preenchimento obrigatório.';
        mensagem.espera = 3000;
        mensagem.exibe();
        return false;
    }
    return false;
});

$('#logar').click(function (e) {
    var parametros = {
        acao: 'logar_nova_sessao',
        senha: $('.senha').val(),
        email: $('.email').val()
    };

    var dados = {};
    var data, status, xhr;

    $.post('login.php', parametros, function (data, status, xhr) {
        dados = toJson(data);
    }).success(function () {
        if (dados.erro === true) {
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 3000;
            mensagem.exibe();
            return false;
        } else {
            registraLogAcesso(URL_ATUAL);
            location.href = APP_HTTP + 'index.php';
        }
    }).error(function (xhr) {
        mensagem.titulo = dados.mensagem;
        mensagem.espera = 3000;
        mensagem.exibe();
        return false;
    });
    return false;
});

$('.esqueceu-senha').click(function (e) {
    if (!isEmpty($('.email-esqueceu-senha').val())) {

        //Gera senha provisória para envio por email
        nova_senha = Math.random().toString(36).substring(0, 10).replace('0.', '');

        //Envia email com senha provisória
        var parametros = {
            acao: 'envia-email',
            senha: nova_senha,
            email: $('.email-esqueceu-senha').val()
        };
        var dados = {};

        $.post('login.php', parametros, function (r, b, xhr) {
            dados = toJson(r);
        }).success(function () {
            if (dados.erro === true) {
                mensagem.titulo = dados.mensagem;
                mensagem.espera = 3000;
                mensagem.exibe();
                return false;
            } else if (isEmpty(dados)) {
                mensagem.titulo = 'Problemas na conexão com o banco. Verifique ou tente mais tarde.';
                mensagem.espera = 3000;
                mensagem.exibe();
                return false;
            } else if (dados.erro === false) {
                mensagem.titulo = 'Senha enviada para seu email.';
                mensagem.espera = 3000;
                mensagem.exibe();
                return true;
            } else {
                mensagem.titulo = 'Problemas no sistema. Procure o administrador ou tente mais tarde.';
                mensagem.espera = 3000;
                mensagem.exibe();
                return true;
            }
        }).error(function (xhr) {
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 3000;
            mensagem.exibe();
            return false;
        });
    } else {
        mensagem.titulo = 'Campo de preenchimento obrigatório.';
        mensagem.espera = 3000;
        mensagem.exibe();
        return false;
    }
    return false;
});

$('.enviar_mensagem').click(function (e) {
    if (!isEmpty($('#email').val())) {

         //Envia email com senha provisória
        var parametros = {
            acao: 'enviar_mensagem',
            senha: nova_senha,
            nome: $('#nome').val(),
            email: $('#email').val(),
            mensagem: $('#mensagem').val()
        };
        var dados = {};

        $.post('login.php', parametros, function (r, b, xhr) {
            dados = toJson(r);
        }).success(function () {
            if (dados.erro === true) {
                mensagem.titulo = dados.mensagem;
                mensagem.espera = 3000;
                mensagem.exibe();
                return false;
            }
        }).error(function (xhr) {
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 3000;
            mensagem.exibe();
            return false;
        });
    } else {
        mensagem.titulo = 'Campo de preenchimento obrigatório.';
        mensagem.espera = 3000;
        mensagem.exibe();
        return false;
    }
    return false;
});
