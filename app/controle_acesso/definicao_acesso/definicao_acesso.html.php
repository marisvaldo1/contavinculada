<?php $template = new \templates\TemplateBootstrap4pikeAdmin(['definicao_acesso.css'], ['definicao_acesso.js']); ?>
<?php $template->inicioConteudo() ?>

<div class="container">
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fa fa-users"></i> Usuários
                        <a href="#" class="pull-right adicionar fa fa-check fa-2x btn-salva-acesso"></a>
                    </h3>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <div class="divAcesso">
                            <table id="acesso" class="table table-striped table-bordered table-hover display acessos" width="100%">
                                <thead></thead>
                            </table>

                        </div>
                    </div>                            
                </div>
            </div><!-- end card-->
        </div>

        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fa fa-bars opcoes-menu"></i> 
                        <a href="#" class="pull-right adicionar fa fa-check fa-2x btn-salva-acesso"></a>
                    </h3>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <div id="divOpcoesSistema"></div></div>
                </div>
            </div>
        </div>                            
    </div>
</div><!-- end card-->

<!-- Modal Atualizar-->
<div class="modal fade" id="modalCliente" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header text-primary d-flex justify-content-between align-items-center">
                <span>
                    <i class="fa fa-fw fa-exclamation-circle fa-3x"></i>
                </span>
                <h5 class="modal-title">Atualizar Informações</h5>
                <input type="text" id="id_usuario" hidden>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <button id="botao-cancela-modal" type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button id="botao-salvar" type="button" class="btn btn-primary text-light">Salvar</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->

  <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>-->
  <!--<script src="main.js"></script>-->

<?php $template->fimConteudo() ?>
<?php $template->renderiza() ?>

