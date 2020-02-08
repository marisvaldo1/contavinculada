<?php $template = new \templates\TemplateBootstrap4pikeAdmin(['excluir-verba.css'], ['excluir-verba.js']); ?>
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
                <h3><i class="fa fa-2x fa-address-card"></i> Exclusão de verbas</h3>
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
                        <div class="form-group col-md-3">
                            <label for="select-contrato">Contrato</label>
                            <select id="select-contrato" class="form-control">
                                <option value="">Selecione</option>
                            </select>
                        </div>
                        <div class="form-group col-md-1">
                            <label for="select-mes">Mês</label>
                            <select id="select-mes" class="form-control">
                                <option value="0">...</option>
                                <option value="01">Jan</option>
                                <option value="02">Fev</option>
                                <option value="03">Mar</option>
                                <option value="04">Abr</option>
                                <option value="05">Mai</option>
                                <option value="06">Jun</option>
                                <option value="07">Jul</option>
                                <option value="08">Ago</option>
                                <option value="09">Set</option>
                                <option value="10">Out</option>
                                <option value="11">Nov</option>
                                <option value="12">Dez</option>
                            </select>
                        </div>
                        <div class="form-group col-md-1">
                            <label for="select-mes">Ano</label>
                            <select id="select-ano" class="form-control">
                                <option value="0">...</option>
                                <option value="2015">2015</option>
                                <option value="2016">2016</option>
                                <option value="2017">2017</option>
                                <option value="2018">2018</option>
                                <option value="2019">2019</option>
                                <option value="2020">2020</option>
                                <option value="2021">2021</option>
                                <option value="2022">2022</option>
                                <option value="2023">2023</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                                <option value="2026">2025</option>
                                <option value="2027">2025</option>
                                <option value="2028">2025</option>
                                <option value="2029">2025</option>
                                <option value="2030">2025</option>
                            </select>
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