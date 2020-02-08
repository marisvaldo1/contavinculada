<?php $template = new \templates\TemplateBootstrap4pikeAdmin(['cliente.css'], ['cliente.js']); ?>
<?php $template->inicioConteudo() ?>

<?php
if (!verificaAcesso('clientes'))
    location(SITE . 'app/index.php');
?>

<div class="row">
    <div class="col-xl-12">
        <div class="breadcrumb-holder">
            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item">Cadastros</li>
                <li class="breadcrumb-item active">Controle</li>
                <li class="breadcrumb-item active">Clientes</li>
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
                <h3><i class="fa fa-2x fa-users"></i> Clientes
                    <a href="#" class="pull-right adicionar fa fa-plus-circle fa-2x btn-novo-cliente"></a>
                </h3>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <div class="divResumo">
                        <table id="clientes" class="table table-striped table-bordered table-hover display clientes" width="100%"></table>
                    </div>
                </div>
            </div>
        </div><!-- end card-->
    </div>

    <!-- Modal clientes-->
    <div class="modal fade" id="modalCliente" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-primary d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fa fa-fw fa-users fa-2x"></i>
                    </span>
                    <h5 class="modal-title novo-altera">Clientes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card mb-6">
                        <div class="card-body">
                            <form id="formDadosCliente" autocomplete="off" action="#">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="cnpj">CNPJ *</label>
                                        <input type="text" id="id_cliente" hidden>
                                        <input type="text" class="form-control" id="cnpj" required>
                                        <div class="invalid-feedback">Campo obrigatório</div>

                                    </div>
                                    <div class="form-group col-md-8">
                                        <label for="razao">Razão Social *</label>
                                        <input type="text" class="form-control" id="razao" required>
                                        <div class="invalid-feedback">Campo obrigatório</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="endereco">Endereço</label>
                                    <input type="text" class="form-control" id="endereco" placeholder="">
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-5">
                                        <label for="cidade">Cidade</label>
                                        <input type="text" class="form-control" id="cidade">
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
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="cep">CEP</label>
                                        <input type="text" class="form-control" id="cep">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="telefone">Telefone</label>
                                        <input type="text" class="form-control" id="telefone" placeholder="(99)99999-9999">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-8">
                                        <label for="nome_contato">Nome para Contato</label>
                                        <input type="text" class="form-control" id="nome_contato" placeholder="">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="telefone_contato">Telefone para Contato</label>
                                        <input type="text" class="form-control" id="telefone_contato" placeholder="" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-9">
                                        <label for="email">E-Mail</label>
                                        <input type="email" class="form-control" id="email">
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="label-categoria">Categoria</label>
                                        <select id="seleciona-categoria" class="form-control"></select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="decimo_terceiro">Décimo Terceiro (%)</label>
                                        <input type="text" class="form-control" id="decimo_terceiro" value="8.33">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="ferias_abono">Abono Férias (%)</label>
                                        <input type="text" class="form-control" id="ferias_abono" value="11.11">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="multa_fgts">Multa FGTS (%)</label>
                                        <input type="text" class="form-control" id="multa_fgts" value="4.00">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- end card-->
                </div>

                <!-- botões de ação da página -->
                <div class="modal-footer">
                    <button id="botao-cancela-modal" class="btn btn-block btn-lg btn-secondary botoes-navegacao mt-0" type="button" style="display: block" data-dismiss="modal">
                        <span class="texto-botoes-navegacao">Cancelar</span>
                    </button>
                    <button id="botao-salvar" class="btn btn-block btn-lg btn-primary botoes-navegacao mt-0 btn-submit" type="button">
                        <span class="texto-botoes-navegacao">Salvar</span>
                    </button>
                </div>

            </div>
        </div>
    </div>
    <!-- Modal -->

    <!-- Modal excluir-->
    <div class="modal fade" id="modalExcluirCliente" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-primary d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fa fa-fw fa-exclamation-circle fa-3x"></i>
                    </span>
                    <h5 class="modal-title">Excluir Cliente</h5>
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