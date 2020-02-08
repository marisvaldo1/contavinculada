<?php $template = new \templates\TemplateBootstrap4pikeAdmin(['administrador.css'], ['administrador.js']); ?>
<?php $template->inicioConteudo() ?>

<?php
if (!verificaAcesso('administradores'))
    location(SITE . 'app/index.php');
?>

<div class="row">
    <div class="col-xl-12">
        <div class="breadcrumb-holder">
            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item">Inicio</li>
                <li class="breadcrumb-item active">Administradores</li>
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
                <h3><i class="fa fa-2x fa-users"></i> Administradores
                    <a href="#" class="pull-right adicionar fa fa-plus-circle fa-2x btn-novo-administrador"></a>
                </h3>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <div class="divResumo">
                        <table id="administradores" class="table table-striped table-bordered table-hover display administradores" width="100%"></table>
                    </div>
                </div>                            
            </div>
        </div><!-- end card-->
    </div>
    <!-- Modal administradores-->
    <div class="modal fade" id="modalAdministrador" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-primary d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fa fa-fw fa-users fa-2x"></i>
                    </span>
                    <h5 class="modal-title novo-altera"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card mb-6">
                        <div class="card-body">
                            <form id="formDadosAdministrador" autocomplete="off" action="#">
                                <div class="form-row">
                                    <label for="nome_administrador">Nome</label>
                                    <input type="text" id="id_administrador" hidden>
                                    <input type="text" class="form-control" id="nome_administrador" placeholder="" autocomplete="off">
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-8">
                                        <label for="email">Email</label>
                                        <input type="text" class="form-control" id="email">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="telefone">Telefone</label>
                                        <input type="text" class="form-control" id="telefone">
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
    <div class="modal fade" id="modalExcluirAdministrador" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-primary d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fa fa-fw fa-exclamation-circle fa-3x"></i>
                    </span>
                    <h5 class="modal-title">Excluir Administrador</h5>
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