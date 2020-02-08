<?php $template = new \templates\TemplateBootstrap4pikeAdmin(['empresa.css'], ['empresa.js']); ?>
<?php $template->inicioConteudo() ?>

<?php
if (!verificaAcesso('empresas'))
    location(SITE . 'app/index.php');
?>

<div class="row">
    <div class="col-xl-12">
        <div class="breadcrumb-holder">
            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item">Inicio</li>
                <li class="breadcrumb-item active">Empresas</li>
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
                <h3><i class="fa fa-users"></i> Empresas
                    <a href="#" class="pull-right adicionar fa fa-plus-circle fa-2x btn-novo-empresa"></a>
                </h3>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <div class="divResumo">
                        <table id="tab_empresas" class="table table-striped table-bordered table-hover display empresas" width="100%">
                            <thead>
                                <tr class="text-uppercase badge-info">
                                    <th data-titulo="Acao" class="text-center" style="width: 5%;">#</th>
                                    <th data-titulo="Cnpj" class="text-center" style="width: 13%;">Cnpj</th>
                                    <th data-titulo="Razão" class="text-center">Razão</th>
                                    <th data-titulo="Endereço" class="text-center" style="width: 20%;">Endereço</th>
                                    <th data-titulo="Telefone" class="text-center" style="width: 13%;">Telefone</th>
                                    <th data-titulo="e-mail" class="text-center">e-mail</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>                            
            </div>
        </div><!-- end card-->
    </div>

    <!-- Modal empresas-->
    <div class="modal fade" id="modalEmpresa" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-primary d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fa fa-fw fa-users fa-2x">Empresas</i>
                    </span>
                    <h5 class="modal-title novo-altera"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card mb-6">
                        <div class="card-body">
                            <form id="formDadosEmpresa" autocomplete="off" action="#">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="cnpj">CNPJ*</label>
                                        <input type="text" id="id_empresa" hidden>
                                        <input type="text" class="form-control" id="cnpj" required>
                                        <div class="invalid-feedback">Campo inválido</div>
                                    </div>
                                    <div class="form-group col-md-8">
                                        <label for="razao">Razão Social*</label>
                                        <input type="text" class="form-control" id="razao" required>
                                        <div class="invalid-feedback">Campo obrigatório</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="endereco">Endereço*</label>
                                    <input type="text" class="form-control" id="endereco">
                                    <div class="invalid-feedback">Campo obrigatório</div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-5">
                                        <label for="cidade">Cidade*</label>
                                        <input type="text" class="form-control" id="cidade" required>
                                        <div class="invalid-feedback">Campo obrigatório</div>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="estado">UF</label>
                                        <select id="estado" class="form-control">
                                            <option selected>...</option>
                                            <option value="AC">AC</option>
                                            <option value="AL">AL</option>
                                            <option value="AP">AP</option>
                                            <option value="AM">AM</option>
                                            <option value="BA">BA</option>
                                            <option value="CE">CE</option>
                                            <option value="DF">DF</option>
                                            <option value="ES">ES</option>
                                            <option value="GO">GO</option>
                                            <option value="MA">MA</option>
                                            <option value="MT">MT</option>
                                            <option value="MS">MS</option>
                                            <option value="MG">MG</option>
                                            <option value="PA">PA</option>
                                            <option value="PB">PB</option>
                                            <option value="PR">PR</option>
                                            <option value="PE">PE</option>
                                            <option value="PI">PI</option>
                                            <option value="RJ">RJ</option>
                                            <option value="RN">RN</option>
                                            <option value="RS">RS</option>
                                            <option value="RO">RO</option>
                                            <option value="RR">RR</option>
                                            <option value="SC">SC</option>
                                            <option value="SP">SP</option>
                                            <option value="SE">SE</option>
                                            <option value="TO">TO</option>                                           
                                        </select>
                                        <div class="invalid-feedback">Campo obrigatório</div>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="cep">CEP</label>
                                        <input type="text" class="form-control" id="cep">
                                        <div class="invalid-feedback">Campo obrigatório</div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="telefone">Telefone*</label>
                                        <input type="tel" class="form-control form-control-sm" id="telefone" maxlength="15" data-error="Telefone Inválido"
                                               data-original-title="Somente números com DDD" required>
                                        <small id="passwordHelpBlock" class="form-text text-muted">Somente números com DDD</small>

                                        <div class="invalid-feedback">Campo obrigatório</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-group col-md-14">
                                        <label for="email">E-Mail*</label>
                                        <input type="email" class="form-control" id="email" required>
                                        <div class="invalid-feedback">E-mail inválido</div>
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
    <div class="modal fade" id="modalExcluirEmpresa" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-primary d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fa fa-fw fa-exclamation-circle fa-3x"></i>
                    </span>
                    <h5 class="modal-title">Excluir Empresa</h5>
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