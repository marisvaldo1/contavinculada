<?php $template = new \templates\TemplateBootstrap4pikeAdmin(['cargo.css'], ['cargo.js']); ?>
<?php $template->inicioConteudo() ?>

<?php
if (!verificaAcesso('cargos'))
    location(SITE . 'app/index.php');
?>

<div class="row">
    <div class="col-xl-12">
        <div class="breadcrumb-holder">
            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item">Cadastros</li>
                <!--<li class="breadcrumb-item active">Tabelas Básicas</li>-->
                <li class="breadcrumb-item active">Cargos</li>
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
                <h3><i class="fa fa-2x fa-users"></i> Cargos
                    <a href="#" class="pull-right adicionar fa fa-plus-circle fa-2x btn-novo-cargo"></a>
                </h3>
            </div>

            <div class="card-body">
                <div class="divCargo">
                    <table id="cargos" class="table table-striped table-bordered table-responsive-md table-hover display cargos" width="100%">
                        <thead>
                            <tr class="text-uppercase badge-info">
                                <th data-titulo="Acao" class="text-center" style="width: 5%;">#</th>
                                <th data-titulo="Nome" class="text-center">Nome</th>
                                <th data-titulo="Turno" class="text-center" style="width: 5%;">Turno</th>
                                <th data-titulo="Remuneração" class="text-center" style="width: 15%;">Remuneração</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div><!-- end card-->
    </div>

    <!-- Modal cargos-->
    <div class="modal fade" id="modalCargo" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-primary d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fa fa-fw fa-users fa-2x"></i>
                    </span>
                    <h5 class="modal-title novo-altera"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card mb-6">
                        <div class="card-body">
                            <form id="formDadosCargo" autocomplete="off" action="#">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="nome_cargo">Nome do cargo</label>
                                        <input type="text" id="id_cargo" hidden>
                                        <input type="text" class="form-control" id="nome_cargo">
                                        <div class="invalid-feedback">Campo obrigatório</div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="seleciona-turno">Turno</label>
                                        <select id="seleciona-turno" class="form-control">
                                            <option value="1">Diurno</option>
                                            <option value="2">Noturno</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="nome_cargo">Remuneração do cargo</label>
                                        <input type="text" class="form-control text-right money" id="remuneracao_cargo" required>
                                        <div class="invalid-feedback">Campo obrigatório</div>
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

    <!-- Modal excluir-->
    <div class="modal fade" id="modalExcluirCargo" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-primary d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fa fa-fw fa-exclamation-circle fa-3x"></i>
                    </span>
                    <h5 class="modal-title">Excluir Cargo</h5>
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
    </div>
    <!-- Modal -->

</div>

<?php $template->fimConteudo() ?>
<?php $template->renderiza() ?>