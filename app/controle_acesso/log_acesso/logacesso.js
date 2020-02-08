
$(document).ready(function () {

    //Matem menu sobre a opção selecionada
    $('#sidebar-menu ul a').removeClass('active');
    $('#sidebar-menu ul .controle-acesso').click();
    $('#sidebar-menu ul .log-acesso').addClass('active');

    carregaLogAcesso();
});

/*
 * Definições tabela de log de acessos
 */
var tab_logAcesso = $('#tab_logAcesso').DataTable({
    scrollY: '35vh', //Define a quantidade de linhas na tabela e acrescenta scroll automático
    fixedHeader: true,
    paging: true,
    responsive: true,
    destroy: false,
    clear: false,
    searchable: false,
    searching: false,
    info: false,
    ordering: true,
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
    },
    columns: [
        {title: "ACESSO", className: "text-center"},
        {title: "USUARIO", className: "text-center"},
        {title: "DATA", className: "text-center"},
        {title: "URL", className: "text-center"}
    ]
});

var carregaLogAcesso = function () {
    var dados = {};
    var data, status, xhr;
    
    //Todo: Pegar a data selecionada no caledário.
    
    var hoje = new Date();
    hoje = moment(new Date()).format('YYYY') + '-' + moment(new Date()).format('MM') + '-' + moment(new Date()).format('DD') ;
    
    $.post('logacesso.php', {acao: 'listar', data: hoje}, function (data, status, xhr) {
        dados = toJson(data);

    }).success(function () {
        if (dados.erro === true) {
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 2000;
            mensagem.exibe();
            return false;
        } else {
            if (dados.dados.length > 0) {

                $.each(dados.dados, function (i, item) {
                    tab_logAcesso.row.add([
                        item.id_acesso,
                        item.nome,
                        item.dt_hr_acesso,
                        item.url_acessada
                    ]).draw();
                });
            }
        }
    }).error(function (xhr) {
        mensagem.titulo = dados.mensagem;
        mensagem.espera = 5000;
        mensagem.exibe();
        return false;
    });

};
