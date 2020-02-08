<?php $template = new \templates\TemplateBootstrap4pikeAdmin(['usuario.css'], ['usuario.js']); ?>
<?php $template->inicioConteudo() ?>

<?php 
if(!verificaAcesso('usuarios'))
    location(SITE . 'app/index.php');
?>

<div class="row">
    <div class="col-xl-12">
        <div class="breadcrumb-holder">
            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item">Inicio</li>
                <li class="breadcrumb-item active">Usuários</li>
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
                <h3><i class="fa fa-users"></i> Usuários cadastrados
                    <a href="#" class="pull-right adicionar fa fa-plus-circle fa-2x btn-novo-usuario"></a>
                </h3>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <div class="divUsuario">
                        <table id="usuarios" class="table table-striped table-bordered table-hover display usuario" width="100%"></table>
                    </div>
                </div>                            
            </div>
        </div><!-- end card-->
    </div>

    <!-- Modal continua atendimento-->
    <div class="modal fade" id="modalUsuario" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-primary d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fa fa-fw fa-users fa-2x"></i>
                    </span>
                    <h5 class="modal-title"> Usuário</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card mb-6">
                        <div class="card-body">
                            <form class="form-dados-usuario" autocomplete="off" action="#">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <input type="text" id="id_usuario" hidden>
                                        <label for="nome">Nome do Usuário</label>
                                        <input type="text" class="form-control" id="nome" placeholder="" autocomplete="off">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email">Email</label>
                                        <input type="text" class="form-control" id="email" placeholder="" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="nivel-acesso">Nível de Acesso</label>
                                        <select id="nivel-acesso" class="form-control">
                                        </select>
                                    </div>
                                    
                                    <div class="form-group col-md-4">
                                        <label for="senha">Senha</label>
                                        <input type="password" class="form-control" id="senha" placeholder="" autocomplete="off">
                                    </div>
                                    
                                    <div class="form-group col-md-4">
                                        <label for="confirmar-senha">Confirmar Senha</label>
                                        <input type="password" class="form-control" id="confirmar-senha" placeholder="" autocomplete="off">
                                    </div>
                                    
                                    <div class="form-group col-md-12">
                                        <label for="cliente-usuario">Cliente</label>
                                        <select id="cliente-usuario" class="form-control">
                                            <option value="0">...</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="select-empresa">Empresa</label>
                                        <select id="select-empresa" class="form-control" disabled>
                                            <option value="0">...</option>
                                        </select>
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

    <!-- Modal exclui contrato-->
    <div class="modal fade" id="modalExcluirUsuario" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-primary d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fa fa-fw fa-exclamation-circle fa-3x"></i>
                    </span>
                    <h5 class="modal-title">Excluir Usuário</h5>
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