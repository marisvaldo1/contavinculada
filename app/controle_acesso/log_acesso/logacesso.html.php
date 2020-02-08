<?php $template = new \templates\TemplateBootstrap4pikeAdmin(['logacesso.css'], ['logacesso.js']); ?>
<?php $template->inicioConteudo() ?>

<?php
if (!verificaAcesso('logacesso'))
    location(SITE . 'app/index.php');
?>

<div class="row">
    <div class="col-xl-12">
        <div class="breadcrumb-holder">
            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item">Controle de acesso</li>
                <li class="breadcrumb-item active">Log de acesso ao sistema</li>
            </ol>
            <div class="clearfix"></div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card mb-3">
            <div class="card-header">
                <h3><i class="fa fa-2x fa-address-card"></i> Log de acesso ao sistema</h3>
            </div>

            <div class="card-body">
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-5">
                            <label>Usuário</label>
                            <select id="select-empresa" class="form-control">
                                <option value="">Selecione</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="dt_inicio">Data Inicial</label>
                            <input type="text" class="form-control data" value="<?= date('d/m/Y') ?>" id="dt_inicio">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="select-contrato">Data Final</label>
                            <select id="select-contrato" class="form-control">
                                <option value="">Selecione</option>
                            </select>
                        </div>

                        <div class="form-group col-md-1">
                            <label for="label-filtro">&nbsp;</label>
                            <div>
                                <button id="botao-filtrar" type="button" class="btn btn-primary text-light">Filtrar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <div class="divLogAcesso">
                        <table id="tab_logAcesso" class="table table-bordered table-striped table-hover table-responsive-md" width="100%" cellspacing="0">
                            <thead>
                                <tr role="row" class="badge-info">
                                    <th data-titulo="ACESSO" class="text-center"></th>
                                    <th data-titulo="USUÁRIO" class="text-center"></th>
                                    <th data-titulo="DATA" class="text-center"></th>
                                    <th data-titulo="URL" class="text-center"></th>
                                </tr>
                            </thead>
                        </table>                                        
                    </div>
                </div>                            
            </div>
        </div><!-- end card-->
    </div>
</div>

<?php $template->fimConteudo() ?>
<?php $template->renderiza() ?>