<?php $template = new \templates\TemplateBootstrap4pikeAdmin(['perfil_usuario.css'], ['perfil_usuario.js']); ?>
<?php $template->inicioConteudo() ?>

<?php
if (!verificaAcesso('usuarios'))
    location(SITE . 'app/index.php');
?>

<div class="row">
    <div class="col-xl-12">
        <div class="breadcrumb-holder">
            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item">Inicio</li>
                <li class="breadcrumb-item active">Perfil do Usuário</li>
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
                <h3><i class="fa fa-user"></i> Detalhes do usuário</h3>								
            </div>

            <div class="card-body">
                <form action="#" method="post" enctype="multipart/form-data">
                    <div class="row">	
                        <div class="col-lg-9 col-xl-9">

                            <div class="row">				
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Nome Completo</label>
                                        <input class="form-control" id="nome" type="text" required />
                                        <div class="invalid-feedback" >Campo obrigatório</div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input class="form-control" id="email" type="email" readonly />
                                    </div>
                                </div>  
                            </div>

                            <div class="row">				
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Senha Atual</label>
                                        <input class="form-control" id="senha" type="password" readonly/>
                                    </div>
                                </div>              			                                

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Nova Senha</label>
                                        <input class="form-control" id="nova_senha" type="password" required />
                                        <div class="invalid-feedback" >Campo obrigatório</div>
                                    </div>
                                </div>   
                            </div>
                            <div class="row">				
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Nível de Acesso</label>
                                        <input class="form-control" id="nivel_acesso" type="text" value="Administrator" readonly/>
                                    </div>
                                </div>   
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="button" class="btn btn-primary" id="botao-salvar">Salvar</button>
                                </div>
                            </div>

                        </div>
                    </div>								
                </form>										
            </div>	
            <!-- end card-body -->								

        </div>
        <!-- end card -->					

    </div>
    <!-- end col -->	

</div>

<?php $template->fimConteudo() ?>
<?php $template->renderiza() ?>