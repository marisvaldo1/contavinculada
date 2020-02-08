<?php $template = new \templates\TemplateBootstrap4pikeAdmin(['captura.css'], ['captura.js']); ?>
<?php $template->inicioConteudo() ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>

<?php
if (!verificaAcesso('lancamentos'))
    location(SITE . 'app/index.php');
?>

<div class="row">
    <div class="col-xl-12">
        <div class="breadcrumb-holder">
            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item">Conta Corrente</li>
                <li class="breadcrumb-item active">Captura dados</li>
            </ol>
            <div class="clearfix"></div>
        </div>
    </div>
</div><!-- end row -->

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card mb-3">
            <div class="card-header">
                <h3><i class="fa fa-2x fa-address-card"></i> Captura lançamentos para o Contrato
                    <div style="float: right;">Modelo de planilha para retenção&nbsp;<i class="fa fa-1x fa-arrow-circle-o-right fa-2x text-danger" tyle="color:red"></i>
                        <a href="..\..\..\ScriptBanco\PlanilhaPadraoContaVinculada.xlsx" class="pull-right planilha fa fa-table fa-2x obter-planilha" download data-toggle="tooltip" data-placement="right" title="" data-original-title="Modelo de Planilha para Retenção">
                        </a>
                    </div>
                </h3>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <div class="container">
                        <div class="row">

                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h3><i class="fa fa-line-chart"></i> Dados para a captura</h3>
                                    </div>

                                    <div class="card-body">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label>Empresa *</label>
                                                <select id="select-empresa" class="form-control">
                                                    <option value="">Selecione</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="select-contrato">Contrato *</label>
                                                <select id="select-contrato" class="form-control">
                                                    <option value="">Selecione</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label for="select-mes">Mês *</label>
                                                <select id="select-mes" class="form-control">
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
                                            <div class="form-group col-md-3">
                                                <label for="select-mes">Ano *</label>
                                                <select id="select-ano" class="form-control">
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
                                                    <option value="2026">2026</option>
                                                    <option value="2027">2027</option>
                                                    <option value="2028">2028</option>
                                                    <option value="2029">2029</option>
                                                    <option value="2030">2030</option>
                                                    <option value="2031">2031</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>

                                    <label for="select-mes" class="labelObservacao"> * Campos Obrigatórios </label>
                                    <div class="card-footer la text-muted">
                                        <div class="form-group col-md-6">
                                            <label for="select-mes"> &nbsp; </label>
                                        </div>

                                    </div>
                                </div><!-- end card-->
                            </div>

                            <!-- Dados do arquivo a ser capturado -->
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h3><i class="fa fa-line-chart"></i> Captura dados para o Contrato</h3>
                                    </div>

                                    <form id="formUpload" data-toggle="validator" role="form" method="post" enctype="multipart/form-data">
                                        <div class="card-body">
                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <input type="text" id="nome-empresa" hidden>

                                                    <label>Selecione o arquivo para a captura *</label>
                                                    <span class="btn btn-primary">
                                                        <input type="file" name="upload" id="fileUpload" class="hide" accept=".xlsx, .xls">
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Observação para a captura -->
                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <label>Observações da Captura *</label>
                                                    <textarea class="form-control objeto-contrato tamano-text-area-observacao" id="observacao"></textarea>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="card-footer small text-muted text-right">
                                            <button id="botao-cancela-modal" type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            <button id="botao-captura" type="button" class="btn btn-primary text-light">Capturar</button>
                                        </div>

                                        <!-- Modal Confirmar captura-->
                                        <div class="modal fade" id="modalCaptura" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header text-primary d-flex justify-content-between align-items-center">
                                                        <span>
                                                            <i class="fa fa-fw fa-exclamation-circle fa-3x"></i>
                                                        </span>
                                                        <h5 class="modal-title">Captura verbas</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button id="botao-cancela-modal" type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                        <button id="confirma-botao-captura" type="submit" class="btn btn-primary text-light">Confirma</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal -->
                                    </form>

                                </div><!-- end card-->
                            </div><!-- Fim dados do arquivo a ser capturado -->

                        </div>
                    </div>

                </div>
            </div>

        </div><!-- end card-->
    </div>
</div>

<?php $template->fimConteudo() ?>
<?php $template->renderiza() ?>