<?php $template = new \templates\TemplateBootstrap4pikeAdmin(['empregado.css'], ['empregado.js']); ?>
<?php $template->inicioConteudo() ?>

<?php
if (!verificaAcesso('empregados'))
    location(SITE . 'app/index.php');
?>

<div class="row">
    <div class="col-xl-12">
        <div class="breadcrumb-holder">
            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item">Cadastros</li>
                <!--<li class="breadcrumb-item active">Tabelas Básicas</li>-->
                <li class="breadcrumb-item active">Empregados</li>
            </ol>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card mb-3">
            <div class="card-header">
                <h3><i class="fa fa-2x fa-address-card"></i> Empregados
                    <a href="#" class="pull-right adicionar fa fa-plus-circle fa-2x btn-novo-empregado"></a>
                </h3>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <div class="divEmpregado">
                        <table id="tab_empregados" class="table table-striped table-bordered table-hover display empregados" width="100%">
                            <thead>
                                <tr class="text-uppercase badge-info">
                                    <th data-titulo="Acao" class="text-center" style="xwidth: 5%;">#</th>
                                    <th data-titulo="cpf" class="text-center" style="xwidth: 12%;">cpf</th>
                                    <th data-titulo="Nome" class="text-center" style="xwidth: 25%;">Nome</th>
                                    <th data-titulo="Cargo" class="text-center" style="xwidth: 15%;">Cargo</th>
                                    <th data-titulo="Turno" class="text-center" style="xwidth: 5%;">Turno</th>
                                    <th data-titulo="Observação" class="text-center" style="xwidth: 30%;">Obsercação</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div><!-- end card-->
    </div>

    <!-- Modal insere / altera cadastro-->
    <div class="modal fade" id="modalEmpregado" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-primary d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fa fa-fw fa-users fa-2x"></i>
                    </span>
                    <h5 class="modal-title">Novo Empregado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card mb-6">
                        <div class="card-body">
                            <form id="formDadosEmpregado" data-toggle="validator" autocomplete="off" action="#">
                            <!-- <form id="formUpload" data-toggle="validator" role="form" method="post" enctype="multipart/form-data"> -->
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="cpf">CPF *</label>
                                        <input type="text" id="id_empregado" hidden>
                                        <input type="text" class="form-control" id="cpf" required>
                                        <div class="invalid-feedback">Valor inválido</div>
                                    </div>
                                    <div class="form-group col-md-8">
                                        <label for="nome">Nome do Empregado *</label>
                                        <input type="text" class="form-control" id="nome" required>
                                        <div class="invalid-feedback">Campo obrigatório</div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label id="label-cargo" for="seleciona-cargo">Cargo</label>
                                        <select id="seleciona-cargo" class="form-control">
                                            <option value="0">Selecione</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label id="label-empresa" for="seleciona-empresa">Empresa</label>
                                        <select id="seleciona-empresa" class="form-control">
                                            <option value="0">Selecione</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="dt_admissao">Data Admissão *</label>
                                        <input type="text" class="form-control data" value="<?= date('d/m/Y') ?>" id="dt_admissao">

                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="dt_desligamento">Data Desligamento</label>
                                        <input type="text" class="form-control data" value="<?= date('d/m/Y') ?>" id="dt_desligamento">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="dt_admissao">Observações</label>
                                        <textarea class="form-control tamanho-text-area-observacao" id="observacao"></textarea>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div><!-- end card-->
                </div>
                <div class="modal-footer">
                    <button id="botao-cancela-modal" type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button id="botao-salvar" type="button" class="btn btn-primary text-light">Salvar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->

    <!-- Modal Atendimento Existente-->
    <div class="modal fade" id="modalExcluirEmpregado" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-primary d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fa fa-fw fa-exclamation-circle fa-3x"></i>
                    </span>
                    <h5 class="modal-title">Excluir Empregado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <button id="botao-cancela-modal" type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button id="botao-excluir" type="button" class="btn btn-primary text-light">Confirma</button>
                </div>
            </div>
        </div>
    </div><!-- Modal -->

    <!-- Modal Captura empregados de planilha-->
    <div class="modal fade" id="modalCapturaEmpregados" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-primary d-flex justify-content-between align-items-center">
                    <div class="col-md-12">
                        <div class="form-group has-feedback col-md-6 divArquivoCriterios">
                            <h5 class="modal-title">Captura Empregados</h5>
                            <div class="panel panel-info text-center">
                                <!--<div class="panel-heading">...</div>-->
                                <div class="input-group">
                                    <label class="input-group-btn">
                                        <span class="btn btn-primary">
                                            <input type="file" name="arquivoCaptura[]" id="arquivoCaptura" class="hide">
                                        </span>
                                    </label>
                                    <input type="text" class="form-control" readonly>
                                </div>
                                <h4 id='loading-lista-de-arquivos-criterios' class="hide">Carregando...</h4>
                                <div class="text-left" id="message-lista-de-arquivos-criterios"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="botao-cancela-modal" type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button id="botao-captura" type="button" class="btn btn-primary text-light">Confirma</button>
                </div>
            </div>
        </div>
    </div><!-- Modal -->
</div>

<?php $template->fimConteudo() ?>
<?php $template->renderiza() ?>