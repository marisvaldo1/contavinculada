<?php $template = new \templates\TemplateBootstrap4pikeAdmin(['pagamento.css'], ['pagamento.js']); ?>
<?php $template->inicioConteudo() ?>

<?php
if (!verificaAcesso('lancamentos'))
    location(SITE . 'app/index.php');
?>

<div class="row">
    <div class="col-xl-12">
        <div class="breadcrumb-holder">
            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item">Controle de Contratos</li>
                <li class="breadcrumb-item active">Pagamentos</li>
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
                <h3><i class="fa fa-2x fa-table"></i> Controle de pagamentos de Contratos</h3>
            </div>

            <div class="card-body">
                <div class="row">

                    <!-- Contratos -->
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-5">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h3><i class="fa fa-line-chart"></i> Contratos</h3>
                            </div>

                            <div class="card-body altura-tab-empregados-contrato">
                                <div class="divContrato">
                                    <table id="contratoSistema" class="table table-striped table-bordered table-responsive-md table-hover display cargos" width="100%">
                                        <thead>
                                            <tr class="text-uppercase badge-info">
                                                <th data-titulo="#" class="text-center" style="width: 1%;"></th>
                                                <th data-titulo="Contrato" class="text-center" style="width: 3%;">Contrato</th>
                                                <th data-titulo="Cliente" class="text-center" style="width: 35%;">Cliente</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div><!-- end card-->
                    </div>

                    <!-- Pagamentos do contrato -->
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-7">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h3><i class="fa fa-line-chart"></i> Pagamentos <span id="contratoPagamento"></span></h3>
                            </div>

                            <div class="card-body altura-tab-empregados-contrato">
                                <div class="divPagamentos">
                                    <table id="tab_pagamentos" class="table table-striped table-bordered table-responsive-md table-hover display" width="100%">
                                        <thead>
                                            <tr class="text-uppercase badge-info">
                                                <th data-titulo="#" class="text-center" style="width: 1%;">#</th>
                                                <th data-titulo="PARCELA" class="text-center" style="width: 3%;">NºPAR</th>
                                                <th data-titulo="VENCTO" class="text-center" style="width: 10%;">VENCTO</th>
                                                <th data-titulo="PARCELA" class="text-rigth" style="width: 15%;">PARCELA</th>
                                                <th data-titulo="PAGTO" class="text-center" style="width: 10%;">PAGTO</th>
                                                <th data-titulo="PAGO" class="text-rigth" style="width: 15%;">PAGO</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr role="row" class="badge-info">
                                                <td colspan="3">&nbsp;</td>
                                                <td><strong><span id="totalParcela"></span></strong></td>
                                                <td>&nbsp;</td>
                                                <td><strong><span id="totalPagamento"></span></strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div><!-- end card-->
                    </div>
                    <!-- Fim dos contratos -->

                </div>                            
                <!--</div>-->

                <!--</div>-->
            </div>            

        </div><!-- end card-->
    </div>

    <!-- Modal Pagamentos-->
    <div class="modal fade" id="modalPagamento" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-primary d-flex justify-content-between align-items-center">

                    <span>
                        <i class="fa fa-fw fa-line-chart fa-2x"></i> Controle de pagamentos de Contratos
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
                                    <div class="form-group col-md-2">
                                        <label for="id_parcela">Parcela</label>
                                        <input type="text" id="id_contrato" hidden>
                                        <input type="text" id="id_cliente" hidden>
                                        <input type="text" class="form-control text-center" id="id_parcela" readonly>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="data_vencimento">Vencimento</label>
                                        <input type="text" class="form-control data text-center" value="<?= date('d/m/Y') ?>" id="data_vencimento">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="data_pagamento">Pagamento</label>
                                        <input type="text" class="form-control data text-center" value="<?= date('d/m/Y') ?>" id="data_pagamento">

                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="valor_pagamento">Valor Pago</label>
                                        <input type="text" class="form-control text-right money" id="valor_pagamento">
                                        <div class="invalid-feedback">Campo obrigatório</div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="observacao_parcela">Observação</label>
                                        <!--<input type="text" class="form-control text-center" id="observacao_parcela">-->
                                        <textarea class="form-control objeto-contrato" id="observacao_parcela"></textarea>

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
    </div><!-- Modal -->

    <!-- Modal excluir-->
    <div class="modal fade" id="modalExcluirPagamento" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-primary d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fa fa-fw fa-exclamation-circle fa-3x"></i>
                    </span>
                    <h5 class="modal-title">Excluir Pagamento</h5>
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
<!-- end row -->

<?php $template->fimConteudo() ?>
<?php $template->renderiza() ?>