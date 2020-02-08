<?php $template = new \templates\TemplateBootstrap4pikeAdmin(['extrato.css'], ['extrato.js']); ?>
<?php $template->inicioConteudo() ?>

<?php
if (!verificaAcesso('extratos'))
    location(SITE . 'app/index.php');
?>

<div class="row">
    <div class="col-xl-12">
        <div class="breadcrumb-holder">
            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item">Conta Corrente</li>
                <li class="breadcrumb-item active">Extrato</li>
            </ol>
            <div class="clearfix"></div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card mb-3">
            <div class="card-header">
                <h3><i class="fa fa-2x fa-address-card"></i> Extrato de Contas Correntes</h3>
            </div>

            <div class="card-body">
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-3">
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

                        <div class="form-group col-md-6">
                            <label for="nome">Observações da Retenção</label>
                            <input type="text" class="form-control" id="observacao_retencao" required>
                            <div class="invalid-feedback">Campo obrigatório</div>
                        </div>
                        <div class="form-group col-md-5">
                            <label for="nome">Observações da liberação</label>
                            <input type="text" class="form-control" id="observacao_liberacao" required>
                            <div class="invalid-feedback">Campo obrigatório</div>
                        </div>
                        <div class="form-group col-md-1">
                            <label for="label-filtro">&nbsp;</label>
                            <div>
                                <button id="botao-filtrar" type="button" class="btn btn-primary text-light" style="width: 90px;">Filtrar</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="table-responsive">
                <div class="divExtrato">
                    <table id="tab_extrato" class="table table-bordered table-striped table-hover table-responsive-md" width="100%" cellspacing="0">
                        <thead>
                            <tr role="row" class="badge-info">
                                <th data-titulo="Ano" class="text-center">Mes</th>
                                <th data-titulo="Mês" class="text-center">Mes</th>
                                <th data-titulo="Empregado" class="text-left"></th>
                                <th data-titulo="Ação" class="text-center"></th>
                                <th data-titulo="Décimo Terceiro" class="text-center"></th>
                                <th data-titulo="Abono de Férias" class="text-center"></th>
                                <th data-titulo="Multa FGTS" class="text-center"></th>
                                <th data-titulo="Impacto sobre 13º" class="text-center"></th>
                                <th data-titulo="Impacto Férias / Abono" class="text-center"></th>
                                <th data-titulo="Total<br>Retido/Liberado" class="text-center"></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr role="row" class="badge-info">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><strong><span id="totalDecimoTerceiro"></span></strong></td>
                                <td><strong><span id="totalFeriasAbono"></span></strong></td>
                                <td><strong><span id="totalMultaFGTS"></span></strong></td>
                                <td><strong><span id="totalImpactoEncargos"></span></strong></td>
                                <td><strong><span id="totalImpactoFeriasAbono"></span></strong></td>
                                <td><strong><span id="totalRetidoLiberado"></span></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div><!-- end card-->
</div>
</div>

<?php $template->fimConteudo() ?>
<?php $template->renderiza() ?>