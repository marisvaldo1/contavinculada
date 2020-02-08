$(document).ready(function () {
    //Matem menu sobre a opção selecionada
    $('#sidebar-menu ul a').removeClass('active');
    $('#sidebar-menu ul .cadastros').click();
    $('#sidebar-menu ul .controle').click();
    $('#sidebar-menu ul .usuarios').addClass('active');

     var data, status, xhr;

    $.post(APP_HTTP + 'cadastro/usuarios/usuario.php', {acao: 'perfilUsuario'}, function (data, status, xhr) {
        dados = toJson(data);
    }).success(function () {
        if (dados.erro === true) {
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 5000;
            mensagem.exibe();
            return false;
        } else {
            $("#nome").val(dados.nome);
            $("#email").val(dados.email);
            $("#senha").val(dados.senha);
            $("#nivel_acesso").val(dados.acesso);
        }
    }).error(function (xhr) {
        mensagem.titulo = dados.mensagem;
        mensagem.espera = 5000;
        mensagem.exibe();
        return false;
    });
});

//Valida campo
function validaCampo(campo) {
    if ($(campo).val() === '') {
        field = {id: campo, erro: true};
        tratarCampoLupa(field);
        $(campo).focus();
        return false;
    }
    field = {id: campo, erro: false};
    tratarCampoLupa(field);
    return true;
}

$(document).on('click', '#botao-salvar', function () {

    //Valida os campos da tela
    if (!validaCampo('#nome'))
        return false;

    //Valida os campos da tela
    if (!validaCampo('#nova_senha'))
        return false;

    //capturando todos os campos input do formulario
    var parametros = {
        acao: ($('#id_usuario').val() === '') ? 'novo' : 'alterar',
        id_usuario: $('#id_usuario').val(),
        nome: $('#nome').val(),
        nova_senha: $('#nova_senha').val(),
        email: $('#email').val(),
        empresa: $('#empresa-usuario').val()
    };
    var dados = {};

    $.post(APP_HTTP + 'cadastro/usuarios/usuario.php', parametros, function (data, status, xhr) {
        dados = toJson(data);

    }).success(function () {
        if (dados.erro === true) {
            mensagem.titulo = dados.mensagem;
            console.log(dados.mensagem);
            mensagem.espera = 5000;
            mensagem.exibe();
            return false;
        } else {
            mensagem.titulo = 'Perfil alterado com sucesso.';
            console.log(dados.mensagem);
            mensagem.espera = 2000;
            mensagem.exibe();

            //return true;
            location.href = APP_HTTP + 'index.php';
        }
    }).error(function (xhr) {
        if (xhr.status === 401) {
        }
        return false;
    });
});
