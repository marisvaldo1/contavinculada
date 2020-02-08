<?php $template = new \templates\TemplateBootstrap4pikeAdmin(['indice.css'], ['indice.js']); ?>
<?php $template->inicioConteudo() ?>

<?php 
if(!verificaAcesso('indices'))
    location(SITE . 'app/index.php');
?>

<div class="row">
    <div class="col-xl-12">
        <div class="breadcrumb-holder">
            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item">Cadastros</li>
                <li class="breadcrumb-item active">Tabelas Básicas</li>
                <li class="breadcrumb-item active">Índices de Retenção</li>
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
                <h3><i class="fa fa-2x fa-calculator"></i> Índices de Retenção
                    <a href="#" class="pull-right adicionar fa fa-plus-circle fa-2x btn-novo-indice"></a>
                </h3>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <div class="divIndice">
                        <table id="tab_indices" class="table table-striped table-bordered table-hover display indices" width="100%">
                            <thead>
                                <tr class="text-uppercase badge-info">
                                    <th data-titulo="Acao" class="text-center" style="width: 5%;"></th>
                                    <th data-titulo="Nome" class="text-center">Nome</th>
                                    <th data-titulo="Percentual" class="text-center" style="width: 10%;"></th>
                                </tr>
                            </thead>
                         </table>                        
                    </div>
                </div>                            
            </div>
        </div><!-- end card-->
    </div>

    <!-- Modal novo lançamento-->
    <div class="modal fade" id="modalIndice" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-primary d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fa fa-fw fa-users fa-2x"></i>
                    </span>
                    <h5 class="modal-title">Índices de Retenção</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card mb-6">
                        <div class="card-body">
                            <form autocomplete="off" action="#">
                                <div class="form-row">
                                    <div class="form-group col-md-8">
                                        <label for="nome_indice">Nome do Índice de Retenção</label>
                                        <input type="text" id="id_indice" hidden>
                                        <input type="text" class="form-control" id="nome_indice" placeholder="" autocomplete="off">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="percentual_indice">Percentual</label>
                                        <input type="text" class="form-control" id="percentual_indice" placeholder="" autocomplete="off">
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
    <div class="modal fade" id="modalExcluirIndice" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-primary d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fa fa-fw fa-exclamation-circle fa-3x"></i>
                    </span>
                    <h5 class="modal-title">Excluir Índice de Retenção</h5>
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