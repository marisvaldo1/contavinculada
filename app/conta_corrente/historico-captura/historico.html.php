<?php $template = new \templates\TemplateBootstrap4pikeAdmin(['historico.css'], ['historico.js']); ?>
<?php $template->inicioConteudo() ?>

<?php
if (!verificaAcesso('extratos'))
    location(SITE . 'app/index.php');

if ($filtro)
    echo '<input type="text" id="filtro" hidden value=' . json_encode($filtro) . '>';
?>

<div class="row">
    <div class="col-xl-12">
        <div class="breadcrumb-holder">
            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item">Conta Corrente</li>
                <li class="breadcrumb-item active">Histórico Captura</li>
            </ol>
            <div class="clearfix"></div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card mb-3">
            <div class="card-header">
                <h3><i class="fa fa-2x fa-address-card"></i> Histórico de capturas de dados de contrato</h3>
            </div>

            <div class="card-body">
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-5">
                            <input type="text" id="filtro" hidden>
                            <label>Empresa</label>
                            <select id="select-empresa" class="form-control">
                                <option value="">Selecione</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
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
                                <option value="2026">2026</option>
                                <option value="2027">2027</option>
                                <option value="2028">2028</option>
                                <option value="2029">2029</option>
                                <option value="3020">2030</option>
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
                    <div class="divCaptura">
                        <table id="tab_captura" class="table table-bordered table-striped table-hover table-responsive-md" width="100%" cellspacing="0">
                            <thead>
                                <tr role="row" class="badge-info">
                                    <th data-titulo="Ação" class="text-center" style="width: 5%;"></th>
                                    <th data-titulo="Ano" class="text-center" style="width: 5%;"></th>
                                    <th data-titulo="Mês" class="text-center" style="width: 5%;"></th>
                                    <th data-titulo="Contrato" class="text-center" style="width: 5%;"></th>
                                    <th data-titulo="Planilha Capturada" class="text-left"></th>
                                    <th data-titulo="Status" class="text-center" style="width: 15%;"></th>
                                    <th data-titulo="Data" class="text-center" style="width: 15%;"></th>
                                </tr>
                            </thead>
                        </table>                                        
                    </div>
                </div>                            
            </div>
        </div><!-- end card-->
    </div>

    <!-- Modal excluir-->
    <div class="modal fade" id="modalExcluirHistoricoCaptura" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-primary d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fa fa-fw fa-exclamation-circle fa-3x"></i>
                    </span>
                    <h5 class="modal-title danger">Excluir a Captura</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-primary d-flex justify-content-between align-items-center">
                    <input type="text" id="id_captura" hidden>
                    <h5 class="modal-title">
                        <ol>Ao excluir a captura as seguintes informações serão excluídas.<hr>
                            <h6 class="modal-title">
                                <li>Empregados</li>
                                <li>Cargos</li>
                                <li>Lançamentos de Retenções</li>
                                <li>Lançamentos de Liberações</li>
                        </ol>
                    </h5>
                    </h6>
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