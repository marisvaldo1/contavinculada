<?php $template = new \templates\TemplateBootstrap4pikeAdmin(['contrato_sistema.css'], ['contrato_sistema.js']); ?>
<?php $template->inicioConteudo() ?>

<?php
if (!verificaAcesso('contrato_sistema'))
    location(SITE . 'app/index.php');
?>

<div class="row">
    <div class="col-xl-12">
        <div class="breadcrumb-holder">
            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item">Controle de Contratos</li>
                <li class="breadcrumb-item active">Contratos</li>
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
                <h3><i class="fa fa-2x fa-users"></i> Contratos de utilização do sistema
                    <a href="#" class="pull-right adicionar fa fa-plus-circle fa-2x btn-novo-contrato"></a>
                </h3>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <div class="divContratoSitema">
                        <table id="contratos" class="table table-striped table-bordered table-responsive-md table-hover display cargos" width="100%">
                            <thead>
                                <tr class="text-uppercase badge-info">
                                    <th data-titulo="Ação" class="text-center" style="width: 6%;">Ação</th>
                                    <th data-titulo="Cnpj" class="text-center" style="width: 15%;">Cnpj</th>
                                    <th data-titulo="Razão" class="text-center" style="width: 20%;">Razão</th>
                                    <th data-titulo="Contrato" class="text-center" style="width: 5%;">Contrato</th>
                                    <th data-titulo="Dt Início" class="text-center" style="width: 10%;">Dt Início</th>
                                    <th data-titulo="Dt Final" class="text-center" style="width: 10%;">Dt Final</th>
                                    <th data-titulo="Tipo" class="text-center" style="width: 5%;">Tipo</th>
                                    <th data-titulo="Valor" class="text-center" style="width: 15%;">Valor</th>
<!--                                    <th data-titulo="Status" class="text-center" style="width: 5%;">Status</th>-->
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>                            
            </div>
        </div><!-- end card-->
    </div>
    <!--//////////////////////////////////-->
    <div class="modal fade" id="modalContratoSistema" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Dados do Contrato</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Parcelas</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <form id="formDadosContrato" autocomplete="off" action="#">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <input type="text" id="id_contrato" hidden>
                                                <label for="select-cliente">Cliente</label>
                                                <select id="select-cliente" class="form-control">
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="id_contrato">Contrato</label>
                                                <input type="hidden" id="id_cliente">
                                                <input type="text" class="form-control" id="nu_contrato_sistema">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Cnpj</label>
                                                <label class="form-control cnpj"> </label>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="dt_inicio">Data Início</label>
                                                <input type="text" class="form-control data" value="<?= date('d/m/Y') ?>" id="dt_inicio">
                                                <div class="invalid-feedback" >Campo obrigatório</div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="dt_final">Data Final</label>
                                                <input type="text" class="form-control data" value="<?= date('d/m/Y') ?>" id="dt_final">
                                                <div class="invalid-feedback" >Campo obrigatório</div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="tipo_pagamento">Tipo de Pagamento</label>
                                                <select id="tipo_pagamento" class="form-control">
                                                    <option value="M">Mensal</option>
                                                    <option value="A">Anual</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="valor_contrato">Valor do Contrato R$</label>
                                                <input type="text" class="form-control text-right money" id="valor_contrato" required>
                                                <div class="invalid-feedback" >Valor inválido</div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!--Aba de encargos-->
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                            <div class="card-group">
                                <!-- Parcelas -->
                                <div class="card altura-tab-encargos">
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <table id="tab_parcelas"  class="table table-striped table-bordered table-hover display table-responsive-md" width="100%">
                                                <thead>
                                                    <tr role="row" class="badge-info">
                                                        <!--<th data-titulo="AÇÃO" class="text-center">AÇÃO</th>-->
                                                        <th data-titulo="PARCELA" class="text-center">PARCELA</th>
                                                        <th data-titulo="DT VENCIMENTO" class="text-center">DT VENCIMENTO</th>
                                                        <th data-titulo="DT PAGAMENTO" class="text-center">DT PAGAMENTO</th>
                                                        <th data-titulo="VALOR" class="text-center">VALOR</th>
                                                    </tr>
                                                </thead>
                                            </table>                                        
                                        </div>
                                    </div>
                                </div><!-- Parcelas -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="botao-cancela-modal" type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button id="botao-gerar-parcelas" type="button" class="btn btn-primary text-light">Gerar Parcelas</button>
                        <button id="botao-salvar" type="button" class="btn btn-primary text-light">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--//////////////////////////////////-->


    <!-- Modal contratos do sistema-->
    <div class="modal fade" id="modalContratoSistemax" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-primary d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fa fa-fw fa-users fa-2x"></i>
                    </span>
                    <h5 class="modal-title novo-altera">Contrato de utilização do sistema</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card mb-6">
                        <div class="card-body">
                            <form id="formDadosContrato" autocomplete="off" action="#">
                                <div class="form-row">
                                    <div class="form-group col-md-8">
                                        <input type="text" id="id_contrato" hidden>
                                        <label for="select-cliente">Cliente</label>
                                        <select id="select-cliente" class="form-control">
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Cnpj</label>
                                        <label class="form-control cnpj"> </label>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="dt_inicio">Data Início</label>
                                        <input type="text" class="form-control data" value="<?= date('d/m/Y') ?>" id="dt_inicio">
                                        <div class="invalid-feedback" >Campo obrigatório</div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="dt_final">Data Final</label>
                                        <input type="text" class="form-control data" value="<?= date('d/m/Y') ?>" id="dt_final">
                                        <div class="invalid-feedback" >Campo obrigatório</div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="tipo_pagamento">Tipo de Pagamento</label>
                                        <select id="tipo_pagamento" class="form-control">
                                            <option value="M">Mensal</option>
                                            <option value="A">Anual</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="valor_contrato">Valor do Contrato</label>
                                        <input type="text" class="form-control text-right money" id="valor_contrato" required>
                                        <div class="invalid-feedback" >Campo obrigatório</div>
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
    <div class="modal fade" id="modalExcluirContrato" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-primary d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fa fa-fw fa-exclamation-circle fa-3x"></i>
                    </span>
                    <h5 class="modal-title">Excluir Contrato</h5>
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