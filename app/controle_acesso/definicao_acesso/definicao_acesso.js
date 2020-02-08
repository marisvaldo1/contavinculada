var dados = {};

$(document).ready(function () {

    //Matem menu sobre a opção selecionada
    $('#sidebar-menu ul a').removeClass('active');
    $('#sidebar-menu ul .controle-acesso').click();
    $('#sidebar-menu ul .definicao-acesso').addClass('active');

    var carregaAcesso = function () {

        var parametros = {
            acao: 'listar',
            id_usuario: 'CLIENTE',  //Parametro passado somente para o id_cliente não ser null
            definicao_acesso: 'Não mostra usuários com o nível de Cliente' //Parametro não considerado
        };
        var data, status, xhr;
        var dados = {};

        $.post('definicao_acesso.php', parametros, function (data, status, xhr) {
            dados = toJson(data);
        }).success(function () {
            if (dados.erro == true) {
                mensagem.titulo = dados.mensagem;
                mensagem.espera = 5000;
                mensagem.exibe();
                return false;
            } else {
                var cabecalhoAcesso = `
                    <tr>
                        <th data-titulo="Usuário" class="">Usuário</th>
                        <th data-titulo="Email" class="">Email</th>
                    </tr>`;

                $("#divAcesso").html(cabecalhoAcesso);

                var dataSet = [];

                $.each(dados.dados, function (i, item) {
                    dataSet.push([
                        `<a href="#" class="detalhaAcesso" id="${i}" data-nome="${item.nome}" data-id="${item.id_usuario}"><i class="fa fa-search fa-2x"></i></a>&nbsp`,
                        item.nome,
                        item.email
                    ]);
                });

                $('#acesso').DataTable({
                    data: dataSet,
                    columns: [
                        {title: "#", className: "text-left"},
                        {title: "USUÁRIO", className: "text-left"},
                        {title: "EMAIL", className: "text-left"}
                    ]
                });

                //Carrega a tela com as opções marcadas de acordo com o usuário clicado
                $.each($('#menuSistema li input'), function (i) {
                    $('#menuSistema li input')[i].checked = false;
                    $('#menuSistema li input')[i].checked = dados.dados[0].opcoes_acesso.indexOf($('#menuSistema li input')[i].id) > -1;
                });

            }
        }).error(function (xhr) {
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 5000;
            mensagem.exibe();
            return false;
        });
    };

    /*Monta o html com todas as opções disponíveis no sistema 
     * e verifica quais o usuário selecionado tem direito de acesso*/
    opcoesSistema =
        `
        <div id="page-wrap">
	
            <ul class="treeview">
                <!-- Cadastros -->
                <li>
                    <input type="checkbox" name="Cadastros" id="cadastros">
                    <label for="cadastros" class="custom-unchecked">Cadastros</label>
                    <ul>
                         <li>
                             <input type="checkbox" name="cadastros-1" id="cadastros-1">
                             <label for="cadastros-1" class="custom-unchecked">Empresas</label>
                         </li>
                         <li>
                             <input type="checkbox" name="cadastros-2" id="cadastros-2">
                             <label for="cadastros-2" class="custom-unchecked">Cargos</label>
                         </li>
                         <li>
                             <input type="checkbox" name="cadastros-3" id="cadastros-3">
                             <label for="cadastros-3" class="custom-unchecked">Empregados</label>
                         </li>
                         <li class="last">
                             <input type="checkbox" name="cadastros-4" id="cadastros-4">
                             <label for="cadastros-3" class="custom-unchecked">Encargos Sociais</label>
                         </li>
                    </ul>
                </li>
    
                <!-- Contratos -->
                <li>
                    <input type="checkbox" name="Contratos" id="contratos">
                    <label for="tall" class="custom-unchecked">Contratos</label>
                </li>
    
                <!-- Contas Correntes -->
                <li>
                    <input type="checkbox" name="ContasCorrentes" id="contascorrentes">
                    <label for="tall" class="custom-unchecked">Contas Correntes</label>
                    <ul>
                        <li>
                            <input type="checkbox" name="contascorrentes-1" id="contascorrentes-1">
                            <label for="contascorrentes-1" class="custom-unchecked">Verbas</label>
                        </li>
    
                        <!-- Verbas -->
                        <li>
                            <ul>
                                <li>
                                   <input type="checkbox" name="contascorrentes-2-1" id="contascorrentes-2-1">
                                    <label for="contascorrentes-2-1" class="custom-unchecked">Reter Verbas</label>
                                </li>
                                <li>
                                    <input type="checkbox" name="contascorrentes-2-2" id="contascorrentes-2-2">
                                    <label for="contascorrentes-2-2" class="custom-unchecked">Liberar Verbas</label>
                                </li>
                                <li>
                                    <input type="checkbox" name="contascorrentes-2-2" id="contascorrentes-2-2">
                                    <label for="contascorrentes-2-2" class="custom-unchecked">Excluir Verbas</label>
                                </li>
                                <li class="last">
                                    <input type="checkbox" name="contascorrentes-2-3" id="contascorrentes-2-3">
                                    <label for="contascorrentes-2-3" class="custom-unchecked">Historico de captura</label>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <input type="checkbox" name="contascorrentes-3" id="contascorrentes-3">
                            <label for="contascorrentes-3" class="custom-unchecked">Lançamentos</label>
                        </li>
                        <li class="last">
                            <input type="checkbox" name="contascorrentes-4" id="contascorrentes-4">
                            <label for="contascorrentes-4" class="custom-unchecked">Extratos</label>
                        </li>
                    </ul>
                </li>
                
                <!-- Relatórios -->
                <li class="last">
                    <input type="checkbox" name="relatorios" id="relatorios">
                    <label for="relatorios" class="custom-unchecked">Relatorios</label>

                    <ul>
                         <li>
                             <input type="checkbox" name="relatorios-1" id="relatorios-1">
                             <label for="relatorios-1" class="custom-unchecked">Retenções</label>
                         </li>
                         <li>
                             <input type="checkbox" name="relatorios-2" id="relatorios-2">
                             <label for="relatorios-2" class="custom-unchecked">Liberações</label>
                         </li>
                         <li class="last">
                             <input type="checkbox" name="relatorios-3" id="relatorios-3">
                             <label for="relatorios-3" class="custom-unchecked">Extrato</label>
                         </li>
                    </ul>
                </li>
            </ul>
	
	</div>            
    `;

    $('#divOpcoesSistema').html(opcoesSistema);

    carregaAcesso();
});

var opcoesMarcadas = [];

//Verifica o status do checkbox
$('#cadastros').is(':checked');

//Alterar o status do checkbox
// ???

$(document).on('click', '.detalhaAcesso', function () {
    $('.opcoes-menu').html(" Opções do Sistemas - <strong>" + $(this).attr('data-nome') + "</strong>");
    //$('.opcoes-menu').html(" Opções do Sistemas - <strong>" + dados.dados[this.id].nome + "</strong>");
    $('#id_usuario').val($(this).attr('data-id'));

    for (i = 0; i < $('#menuSistema li input').length; i++) {
        //Percorre todos elementos da dataTable e pesquisa se está ou não marcado
        $('#menuSistema li input')[i].checked = false;
        //$('#menuSistema li input')[i].checked = $.inArray($('#menuSistema li input')[i].id, dados.dados[this.id].opcoes_acesso) > -1;
        $('#menuSistema li input')[i].checked = dados.dados[0].opcoes_acesso.indexOf($('#menuSistema li input')[i].id) > -1;
    }
});

$(document).on('click', '.btn-salva-acesso', function () {

    //Capturando todos os campos input do formulario
    var parametros = {
        acao: 'alterar',
        id_usuario: $('#id_usuario').val(),
        opcoes_acesso: opcoesMarcadas
    };

    var dados = {};

    $.post('definicao_acesso.php', parametros, function (r, b, xhr) {
        dados = toJson(r);
    }).success(function () {
        if (dados.erro === true) {
            mensagem.titulo = dados.mensagem;
            console.log(dados.mensagem);
            mensagem.espera = 2000;
            mensagem.exibe();
            return false;
        } else {
            $('#modalAcesso').modal('hide');
            location.href = 'index.php';
        }
    }).error(function (xhr) {
        if (xhr.status === 401) {
        }
        //console.error(xhr.status);
        return false;
    });
});

$(document).on('click', '.alterar', function () {
    var dados = {};

    $.post('acesso.php', {acao: 'listar', id_acesso: this.id}, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro === true) {
            modal.fecha();
            mensagem.titulo = dados.mensagem;
            console.log(dados.mensagem);
            mensagem.espera = 2000;
            mensagem.exibe();
            return false;
        } else {
            $('#id_acesso').val(dados.dados[0].id_acesso);
            $('#nome_acesso').val(dados.dados[0].nome_acesso);
        }
    }).error(function (xhr) {
        if (xhr.status === 401) {
        }
        return false;
    });

    //Mostra o modal com os dados para a alteração
    $("#modalAcesso").modal({
        backdrop: 'static'
    });
});

var opcoes = [
    "Cadastros",
    "Empresas",
    "Cargos",
    "Empregados",
    "Encargos",
    "Contratos",
    
    //"controleAcesso",
    //"DefinicoesAcesso",
    //"LogAcessos",
    
    "contaCorrente",
    "Verbas",
    "ReterVerbas",
    "LiberarVerbas",
    "ExcluirVerbas",
    "historicoCaptura",
    
    "Lancamentos",
    "Extratos",
    
    "relatoriosGerenciais",
    "rel_retencoes",
    "rel_liberacoes",
    "rel_extrato"
];

//Monta um array com todas as opções selecionadas
var verificaOpcao = function (opcao) {
    if ($.inArray(opcao, opcoes) >= 0) {
        opcoesMarcadas.push([opcao]);
        return true;
    }
    return false;
};

/*
 * TreeView
 */
$(function () {
    $('input[type="checkbox"]').change(checkboxChanged);

    function checkboxChanged() {
        var $this = $(this),
                checked = $this.prop("checked"),
                container = $this.parent(),
                siblings = container.siblings();

        container.find('input[type="checkbox"]')
                .prop({
                    indeterminate: false,
                    checked: checked
                })
                .siblings('label')
                .removeClass('custom-checked custom-unchecked custom-indeterminate')
                .addClass(checked ? 'custom-checked' : 'custom-unchecked');

        checkSiblings(container, checked);
    }

    function checkSiblings($el, checked) {
        var parent = $el.parent().parent(),
                all = true,
                indeterminate = false;

        $el.siblings().each(function () {
            return all = ($(this).children('input[type="checkbox"]').prop("checked") === checked);
        });

        if (all && checked) {
            parent.children('input[type="checkbox"]')
                    .prop({
                        indeterminate: false,
                        checked: checked
                    })
                    .siblings('label')
                    .removeClass('custom-checked custom-unchecked custom-indeterminate')
                    .addClass(checked ? 'custom-checked' : 'custom-unchecked');

            checkSiblings(parent, checked);
        } else if (all && !checked) {
            indeterminate = parent.find('input[type="checkbox"]:checked').length > 0;

            parent.children('input[type="checkbox"]')
                    .prop("checked", checked)
                    .prop("indeterminate", indeterminate)
                    .siblings('label')
                    .removeClass('custom-checked custom-unchecked custom-indeterminate')
                    .addClass(indeterminate ? 'custom-indeterminate' : (checked ? 'custom-checked' : 'custom-unchecked'));

            checkSiblings(parent, checked);
        } else {
            $el.parents("li").children('input[type="checkbox"]')
                    .prop({
                        indeterminate: true,
                        checked: false
                    })
                    .siblings('label')
                    .removeClass('custom-checked custom-unchecked custom-indeterminate')
                    .addClass('custom-indeterminate');
        }
    }
});