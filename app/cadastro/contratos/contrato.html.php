<?php $template = new \templates\TemplateBootstrap4pikeAdmin(['contrato.css'], ['contrato.js']); ?>
<?php $template->inicioConteudo() ?>

<?php
if (!verificaAcesso('contrato'))
    location(SITE . 'app/index.php');
?>

<div class="row">
    <div class="col-xl-12">
        <div class="breadcrumb-holder">
            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item">Cadastros</li>
                <!--                <li class="breadcrumb-item active">Tabelas Básicas</li>-->
                <li class="breadcrumb-item active">Contratos</li>
            </ol>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card mb-3">
            <div class="card-header">
                <h3><i class="fa fa-2x  fa-building-o"></i> Contratos
                    <a href="#" class="pull-right adicionar fa fa-plus-circle fa-2x btn-novo-contrato"></a>
                </h3>
            </div>

            <div class="card-body">
                <table id="tab_contratos" class="table table-striped table-bordered table-hover display table-responsive-md contratos" width="100%">
                    <thead>
                        <tr class="text-uppercase badge-info">
                            <th data-titulo="Ação" class="text-center" style="width: 10%;"></th>
                            <th data-titulo="Contrato" class="text-center" style="width: 10%;"></th>
                            <th data-titulo="Razão Social" class="text-center" style="width: 20%;"></th>
                            <th data-titulo="Dt Inicio" class="text-center" style="width: 10%;"></th>
                            <th data-titulo="Dt Final" class="text-center" style="width: 10%;"></th>
                            <th data-titulo="Objeto" class="text-center" style="width: 25%;"></th>
                            <th data-titulo="Valor" class="text-center" style="width: 10%;"></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div><!-- end card-->
    </div>

    <!--<div class="dialog" id="dialog" role="dialog" aria-hidden="true">-->
    <!--</div>-->    

    <!-- Modal novo contrato-->
    <div class="modal fade" id="modalContrato" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="fechar">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>            
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Dados do Contrato</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Encargos Sociais</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Empregados Alocados neste Contrato</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="card altura-tab-dados-contrato">
                                <!--<div class="card altura-tab">-->
                                <div class="card-body-modal-contrato">
                                    <form autocomplete="off" action="#" id="formDadosContrato">
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label for="nu_contrato">Número do Contrato</label>
                                                <input type="text" id="id_contrato" hidden>
                                                <input type="text" class="form-control" id="nu_contrato">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="dt_inicio">Data Inicial *</label>
                                                <input type="text" class="form-control data" value="<?= date('d/m/Y') ?>" id="dt_inicio">
                                                <div class="invalid-feedback" >Campo obrigatório</div>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="dt_final">Data Final</label>
                                                <input type="text" class="form-control data" value="<?= date('d/m/Y') ?>" id="dt_final">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="valor">Valor Total do Contrato</label>
                                                <input type="tel" class="form-control text-right money" id="valor" required>
                                                <div class="invalid-feedback" >Campo obrigatório</div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="select-empresa">Empresa</label>
                                                <select id="select-empresa" class="form-control" disabled></select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Cnpj</label>
                                                <label class="form-control cnpj">Seleciona a empresa </label>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="objeto">Objeto</label>
                                                <textarea class="form-control objeto-contrato tamano-text-area-contrato" id="objeto"></textarea>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!--Aba de encargos-->
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                            <!-- Card entrada da tabela de encargos -->
                            <div class="table-responsive">
                                <div class="card-group">
                                    <!-- Encargos -->
                                    <div class="card altura-tab-encargos">
                                        <!--<div class="card altura-tab">-->
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <table id="tab_encargos"  class="table table-striped table-bordered table-hover display table-responsive-md tab_encargos" width="100%">
                                                    <thead>
                                                        <tr role="row" class="badge-info">
                                                            <th data-titulo="AÇÂO" class="text-center">AÇÂO</th>
                                                            <th data-titulo="ENCARGOS" class="text-center">ENCARGOS</th>
                                                            <th data-titulo="PERCENTUAL" class="text-center cabecalhoPercentual">PERCENTUAL</th>
                                                        </tr>
                                                    </thead>
                                                </table>                                        
                                            </div>
                                        </div>
                                    </div><!-- Encargos -->
                                </div>  
                            </div>  

                            <div class="card-group">
                                <!-- Encargos -->
                                <div class="card">
                                    <div class="card-body-modal-contrato">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="select-encargo">Encargo</label>
                                                <select id="select-encargo" class="form-control">
                                                    <option value="0">Selecione</option>
                                                </select>
                                                <div class="invalid-feedback">Seleção obrigatória</div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="percentual-encargo">Percentual</label>
                                                <div class="input-group">
                                                    <input id="percentual-encargo" type="text" class="form-control text-right" maxlength="5">
                                                    <a class="form adicionar-encargo-contrato mao-link" data-toggle="tooltip" data-placement="left" title="" data-original-title="Adicionar Encargos">
                                                        <span class="text-primary"><i class="fa fa-plus-circle fa-2x"></i></span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            <!-- Card tabela de empregados -->
                            <div class="table-responsive">
                                <!--<div class="card-group">-->
                                <div class="card altura-tab-empregados-contrato">
                                    <!--<div class="card altura-tab">-->
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <table id="tab_empregados" class="table table-bordered table-striped table-hover table-responsive-md" width="100%" cellspacing="0">
                                                <thead>
                                                    <tr role="row" class="badge-info">
                                                        <!--<th data-titulo="#" class="text-center">#</th>-->
                                                        <th data-titulo="CPF" class="text-center">CPF</th>
                                                        <th data-titulo="NOME" class="text-center">NOME</th>
                                                        <th data-titulo="CARGO" class="text-center">NOME</th>
                                                        <th data-titulo="TURNO" class="text-center">NOME</th>
                                                    </tr>
                                                </thead>
                                            </table>                                        
                                        </div>
                                    </div>
                                </div><!-- Encargos -->

                                <!--</div>-->  
                            </div> 

                            <!-- Empregados -->
<!--                            <div class="form-row">
                                <div class="form-group col-md-8">
                                    <label for="select-empregado">Empregados</label>
                                    <select id="select-empregado" class="form-control">
                                        <option value="0">Selecione</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="cpf">Cpf</label>
                                    <div class="input-group">
                                        <input id="cpf" type="text" class="form-control" readonly>
                                        <a class="adicionar-empregado-contrato mao-link" data-toggle="tooltip" data-placement="left" title="" data-original-title="Adicionar Empregado">
                                            <span class="text-primary"><i class="fa fa-plus-circle fa-2x"></i></span>
                                        </a>
                                    </div>
                                </div>
                            </div>-->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="botao-cancela-modal" type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button id="botao-salvar" type="button" class="btn btn-primary text-light">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Modal -->

    <!-- Modal excluir-->
    <div class="modal fade" id="modalExcluirContrato" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="height: 12em;">
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
    </div><!-- Modal -->

</div><!-- row -->

<?php $template->fimConteudo() ?>
<?php $template->renderiza() ?>