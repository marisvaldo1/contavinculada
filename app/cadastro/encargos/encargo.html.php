<?php $template = new \templates\TemplateBootstrap4pikeAdmin(['encargo.css'], ['encargo.js']); ?>
<?php $template->inicioConteudo() ?>

<?php 
if(!verificaAcesso('encargos'))
    location(SITE . 'app/index.php');
?>

<div class="row">
    <div class="col-xl-12">
        <div class="breadcrumb-holder">
            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item">Cadastros</li>
                <li class="breadcrumb-item active">Encargos Sociais</li>
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
                <h3><i class="fa fa-2x fa-linode"></i> Encargos Sociais
                    <a href="#" class="pull-right adicionar fa fa-plus-circle fa-2x btn-novo-encargo"></a>
                </h3>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <div class="divEncargo">
                        <table id="tab_encargos" class="table table-striped table-bordered table-hover display encargos" width="100%">
                            <thead>
                                <tr class="text-uppercase badge-info">
                                    <th class="text-center" style="width: 5%;">Ação</th>
                                    <th class="text-center" style="width: 25%;">Nome</th>
                                    <th class="text-center" style="width: 5%;">Inserção</th>
                                    <th class="text-center" style="width: 3%;">%</th>
                                </tr>
                            </thead>
                         </table>
                        
                    </div>
                </div>                            
            </div>
        </div><!-- end card-->
    </div>

    <!-- Modal novo lançamento-->
    <div class="modal fade" id="modalEncargo" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-primary d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fa fa-fw fa-users fa-2x"></i>
                    </span>
                    <h5 class="modal-title">Encargo</h5>
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
                                        <label for="nome_encargo">Nome do Encargo</label>
                                        <input type="text" id="id_encargo" hidden>
                                        <input type="text" class="form-control" id="nome_encargo">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="percentual_encargo">Percentual</label>
                                        <input id="percentual_encargo" type="text" class="form-control text-right" maxlength="5">
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
    <div class="modal fade" id="modalExcluirEncargo" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-primary d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fa fa-fw fa-exclamation-circle fa-3x"></i>
                    </span>
                    <h5 class="modal-title">Excluir Encargo</h5>
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