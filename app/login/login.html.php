<?php $template = new templates\TemplateBootstrapLogin4pikeAdmin(['login.css'], ['login.js']); ?>
<?php $template->inicioConteudo() ?>

<div class="form">
    <ul class="tab-group">
        <li class="tab active">
            <button type="button" class="button button-block botao-site" style="font-size: 18px;">Caso não tenha senha de acesso, Solicite aqui.</button>
        </li>
    </ul>

    <div class="tab-content">
        <div id="login">
            <form action="" method="post" id="form-login" novalidate>

                <div class="field-wrap">
                    <label>Endereço de Email<span class="req">*</span></label>
                    <input type="email" class="email" required autocomplete="off" value=""/>
                </div>

                <div class="field-wrap">
                    <label>Senha<span class="req">*</span></label>
                    <input type="password" class="senha" required autocomplete="off" value=""/>
                </div>

                <p class="tab"><a href="#esqueceu-senha">Esqueceu a senha?</a></p>
                <button type="button" class="button button-block entrar">Entrar</button>
            </form>
        </div>
        <div id="esqueceu-senha">
            <form>
                <h6 style="text-align: center; color: white">Uma senha provisória será enviada para seu endereço de email</h6>
                <br>

                <div class="top-row">
                    <div class="field-wrap">
                        <label>Endereço de Email<span class="req">*</span></label>
                        <input type="email" class="email-esqueceu-senha" required autocomplete="off"/>
                    </div>

                    <button type="submit" class="button button-block esqueceu-senha">Enviar</button>
            </form>
        </div>
    </div><!-- tab-content -->

</div> <!-- /form -->

<!-- Modal Logado -->
<div class="modal fade" id="modal-logado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Atenção</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Este usuário já está logado em outra sessão. Deseja entrar com esta nova sessão e finalizar a sessão anterior?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Ok</button>
                <!--<button type="button" class="btn btn-primary" id="logar">OK</button>-->
            </div>
        </div>
    </div>
</div>

<?php $template->fimConteudo() ?>
<?php $template->renderiza() ?>
