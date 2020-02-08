<?php $template = new \templates\TemplateBootstrap4pikeAdmin(['rel_extrato.css'], ['rel_extrato.js']); ?>
<?php $template->inicioConteudo() ?>

<?php
if (!verificaAcesso('rel_extrato'))
    location(SITE . 'app/index.php');
?>

<div class="row">
    <div class="col-xl-12">
        <div class="breadcrumb-holder">
            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item">Conta Corrente</li>
                <li class="breadcrumb-item active">Extrato</li>
            </ol>
            <div class="clearfix"></div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card mb-3">
            <div class="card-header">
                <h3><i class="fa fa-2x fa-address-card"></i> Extrato de Contas</h3>
            </div>

            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label>Empresa</label>
                        <select id="select-empresa" class="form-control">
                            <option value="">Selecione</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="select-contrato">Contrato</label>
                        <select id="select-contrato" class="form-control">
                            <option value="">Selecione</option>
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <label for="dataInicio">Mês/Ano Início</label>
                        <input type="text" id="dataInicio" class="input-mesano-monthpicker" />
                    </div>

                    <div class="form-group col-md-2">
                        <label for="dataFim">Mês/Ano Fim</label>
                        <input type="text" id="dataFim" class="input-mesano-monthpicker" />
                    </div>

                    <div class="form-group col-md-3">
                        <label>Empregado</label>
                        <select id="select-empregado" class="form-control">
                            <option value="">Selecione</option>
                        </select>
                    </div>
                    <div class="form-group col-md-1">
                        <label for="label-filtro">&nbsp;</label>
                        <div>
                            <button id="botao-imprimir" type="button" class="btn btn-primary text-light">Imprimir</button>
                        </div>
                    </div>

                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="nome">Observações da Retenção</label>
                        <input type="text" class="form-control" id="observacao_retencao" required>
                        <div class="invalid-feedback">Campo obrigatório</div>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="nome">Observações da liberação</label>
                        <div class="col-20">
                            <input type="text" class="form-control" id="observacao_liberacao" required>
                        </div>
                        <div class="invalid-feedback">Campo obrigatório</div>
                    </div>
                </div>
            </div>

            <!-- Rúbricas para o relatório -->
            <div class="card-footer">
                <h5> Selecione as Rúbricas a serem listadas</h5><br>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <div class="input-group input-group-lg">
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="checkTodos" >
                                <label class="custom-control-label" for="checkTodos">&nbsp;Todas</label>
                            </div>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input chktipo" id="decimo_terceiro" >
                                <label class="custom-control-label" for="decimo_terceiro" >13º Salário</label>
                            </div>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input chktipo" id="ferias_abono" >
                                <label class="custom-control-label" for="ferias_abono">Férias + Abono</label>
                            </div>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input chktipo" id="fgts" >
                                <label class="custom-control-label" for="fgts">FGTS</label>
                            </div>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input chktipo" id="impacto_13" >
                                <label class="custom-control-label" for="impacto_13">Impacto sobre 13º</label>
                            </div>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input chktipo" id="impacto_ferias_abono" >
                                <label class="custom-control-label" for="impacto_ferias_abono">Impacto Férias + Abono</label>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div><!-- end card-->
</div>
</div>

<form id="formRelExtrato" style="display: none">
    <input type="hidden" id="parametros" name="parametros">
</form>

<?php $template->fimConteudo() ?>
<?php $template->renderiza() ?>