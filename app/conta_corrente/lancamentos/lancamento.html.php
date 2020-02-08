<?php $template = new \templates\TemplateBootstrap4pikeAdmin(['lancamento.css'], ['lancamento.js']); ?>
<?php $template->inicioConteudo() ?>

<?php
if (!verificaAcesso('lancamentos'))
    location(SITE . 'app/index.php');
?>

<div class="row">
    <div class="col-xl-12">
        <div class="breadcrumb-holder">
            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item">Conta Corrente</li>
                <li class="breadcrumb-item active">Lançamentos</li>
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
                <h3><i class="fa fa-2x fa-address-card"></i> Lançamentos de Empregados em Contratos</h3>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <div class="container">
                        <div class="row">

                            <!-- Contratos -->
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h3><i class="fa fa-line-chart"></i> Contratos</h3>
                                    </div>

                                    <div class="card-body altura-tab-empregados-contrato">
                                        <div id="divContrato"></div>
                                    </div>
                                </div><!-- end card-->
                            </div>

                            <!-- Empregados do contrato -->
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h3><i class="fa fa-line-chart"></i> Empregados Vinculados ao Contrato</h3>
                                    </div>
                                    <div class="card-body altura-tab-empregados-contrato">
                                        <table id="tab_empregados" class="table table-bordered table-striped table-hover table-responsive-md" width="100%" cellspacing="0">
                                            <thead>
                                                <tr role="row" class="badge-info">
                                                    <th data-titulo="#" class="text-center" style="width: 5%;"></th>
                                                    <th data-titulo="CPF" class="text-center" style="width: 15%;"></th>
                                                    <th data-titulo="NOME" class="text-center"></th>
                                                </tr>
                                            </thead>
                                        </table>                                        
                                    </div>
                                </div><!-- end card-->
                            </div>
                            <!-- Fim dos contratos -->

                        </div>                            
                    </div>

                </div>
            </div>            

        </div><!-- end card-->
    </div>

    <!-- Modal detalhes do atendimento diário completo-->
    <div class="modal fade" id="modalDetalheLancamentoEmpregado" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content atendimentos-diarios">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fa fa-fw fa-info-circle fa-2x"></i>
                    </span>
                    <h5 class="modal-title text-dark">Lançamentos do empregado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card sombra bg-light text-primary redondo">
                        <div class="total"></div>                    
                        <div class="accordion"></div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Modal -->
</div><!-- Modal -->

<?php $template->fimConteudo()?>
<?php $template->renderiza()?>