<?php $template = new \templates\TemplateBootstrap4pikeAdmin(['liberar.css'], ['liberar.js']); ?>
<?php $template->inicioConteudo() ?>

<?php
if (!verificaAcesso('liberar'))
    location(SITE . 'app/index.php');
?>

<!-- Carregando os dados -->
<div class="spinner" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="width: 100%;">
            <!-- <span class="fa fa-spinner fa-spin fa-3x"></span> -->
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="breadcrumb-holder">
            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item">Conta Corrente</li>
                <li class="breadcrumb-item active">Verbas</li>
                <li class="breadcrumb-item active">Liberar</li>
            </ol>
            <div class="clearfix"></div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card mb-3">
            <div class="card-header">
                <h3><i class="fa fa-2x fa-address-card"></i> Liberar Verbas</h3>
            </div>

            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label>Empresa</label>
                        <select id="select-empresa" class="form-control">
                            <option value="">Selecione</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="select-contrato">Contrato</label>
                        <select id="select-contrato" class="form-control">
                            <option value="">Selecione</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="mesano-inicio">Mês/Ano Início</label>
                        <input type="text" id="dataInicio" class="input-mesano-monthpicker" />
                    </div>

                    <div class="form-group col-md-2">
                        <label for="mesano-fim">Mês/Ano Fim</label>
                        <input type="text" id="dataFim" class="input-mesano-monthpicker" />
                    </div>

                    <div class="form-group col-md-3">
                        <label>Empregado</label>
                        <select id="select-empregado" class="form-control">
                            <option value="">Selecione</option>
                        </select>
                    </div>
                    <div class="form-group col-md-1">
                        <label for="label-filtro">&nbsp;</label>
                        <div>
                            <button id="botao-filtrar" type="button" class="btn btn-primary text-light">Filtrar</button>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-5">
                        <label for="nome">Observações da liberação *</label>
                        <div class="col-20">
                            <input type="text" class="form-control" id="observacao_liberacao" required>
                        </div>
                        <div class="invalid-feedback">Campo obrigatório</div>
                    </div>

                    <div class="form-group col-md-5">
                        <label for="nome">Observações da Retenção</label>
                        <input type="text" class="form-control" id="observacao_retencao" required>
                        <div class="invalid-feedback">Campo obrigatório</div>
                    </div>

                    <div class="form-group col-md-2">
                        <div class="col-12">
                            <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" class="custom-control-input" id="liberacao_casada" checked>
                                <label class="custom-control-label" for="liberacao_casada">Liberação casada com verbas de impacto</label>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="table-responsive">
                <div class="divLiberar">
                    <table id="tab_liberacao" class="table table-bordered table-hover table-responsive-md" width="100%" cellspacing="0">
                        <thead>
                            <tr role="row" class="badge-info odd">
                                <th data-titulo="Ano" class="text-center" style="width: 5%;"></th>
                                <th data-titulo="Mês" class="text-center" style="width: 5%;"></th>
                                <th data-titulo="Empregados do Contrato" class="text-center"></th>
                                <th data-titulo="Décimo Terceiro" class="text-center" style="width: 10%;"></th>
                                <th data-titulo="Abono de Férias" class="text-center" style="width: 10%;"></th>
                                <th data-titulo="Multa sobre FGTS" class="text-center" style="width: 10%;"></th>
                                <th data-titulo="Impacto sobre o 13º" class="text-center" style="width: 10%;"></th>
                                <th data-titulo="Impacto sobre Férias / Abono" class="text-center" style="width: 10%;"></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr role="row" class="badge-info">
                                <td>-</td>
                                <td>-</td>
                                <td></td>
                                <td><strong><span id="totalDecimoTerceiro"></span></strong></td>
                                <td><strong><span id="totalFeriasAbono"></span></strong></td>
                                <td><strong><span id="totalMultaFGTS"></span></strong></td>
                                <td><strong><span id="totalImpactoEncargos"></span></strong></td>
                                <td><strong><span id="totalImpactoFeriasAbono"></span></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div><!-- end card-->
</div>

<?php $template->fimConteudo() ?>
<?php $template->renderiza() ?>