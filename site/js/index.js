$('.enviar-mensagem').click(function (e) {
    if ($('#nomeContato').val() !== '' && $('#emailContato').val() !== '' && $('#mensagemContato').val() !== '') {
        //Envia email com senha provis¨®ria
        var parametros = {
            acao: 'enviar_mensagem',
            nomeVisitante: $('#nomeContato').val(),
            emailVisitante: $('#emailContato').val(),
            mensagemVisitante: $('#mensagemContato').val()
        };
        var dados = {};

        $.post('../app/login/login.php', parametros, function (r, b, xhr) {
            //dados = toJson(r);
            dados = JSON.parse(r);

        }).success(function () {
            if (dados.erro === true) {
                //Abre a modal para cadastro
                $("#modalProblemaMensagemEnviada").modal({
                    backdrop: 'static'
                });
                return false;
            } else {
                //Abre a modal para cadastro
                $("#modalMensagemEnviada").modal({
                    backdrop: 'static'
                });

                $('#nomeContato').val('');
                $('#emailContato').val('');
                $('#mensagemContato').val('');
                
                return true;
            }
        }).error(function (xhr) {
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 3000;
            mensagem.exibe();
            return false;
        });
    } else {
        //Abre a modal erro formul¨¢rio
        $("#modalErroPreenchimentoFormulario").modal({
            backdrop: 'static'
        });
        return false;

    }
    return false;
});

$('.enviar-teste').click(function (e) {
     if ($('#nomeTeste').val() !== '' && $('#emailTeste').val() !== '' && $('#mensagemTeste').val() !== '') {
        //Envia email com senha provis¨®ria
        var parametros = {
            acao: 'enviar_mensagem',
            nomeVisitante: $('#nomeTeste').val(),
            emailVisitante: $('#emailTeste').val(),
            mensagemVisitante: $('#mensagemTeste').val()
        };
        var dados = {};

        $.post('../app/login/login.php', parametros, function (r, b, xhr) {
            //dados = toJson(r);
            dados = JSON.parse(r);

        }).success(function () {
            if (dados.erro === true) {
                //Abre a modal para cadastro
                $("#modalProblemaMensagemEnviada").modal({
                    backdrop: 'static'
                });
                return false;
            } else {
                //Abre a modal para cadastro
                $("#modalMensagemEnviada").modal({
                    backdrop: 'static'
                });

                $('#nomeTeste').val('');
                $('#emailTeste').val('');
                $('#mensagemTeste').val('');

                return true;
            }
        }).error(function (xhr) {
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 3000;
            mensagem.exibe();
            return false;
        });
    } else {
        //Abre a modal erro formul¨¢rio
        $("#modalErroPreenchimentoFormulario").modal({
            backdrop: 'static'
        });
        return false;

    }
    return false;
});

$(document).on('click', '#fazer-teste', function () {
    //Abre a modal para cadastro
    $("#modalTeste").modal({
        backdrop: 'static'
    });
   
    return false;
});

