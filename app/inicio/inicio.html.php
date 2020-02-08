<?php $template = new \templates\TemplateBootstrap4pikeAdmin(['inicio.css'], ['inicio.js']); ?>
<?php date_default_timezone_set('America/Sao_Paulo'); ?>
<?php $template->inicioConteudo(); ?>

<?php modelo\Usuario::validaToken(); ?>

<?php $nivel_acesso = $_SESSION["dados_usuario"]->getNivel_acesso(); ?>

<div class="row">
    <div class="col-xl-12">
        <div class="breadcrumb-holder">
            <h1 class="main-title float-left">Dashboard</h1>
            <ol class="breadcrumb float-right">
                <li class="breadcrumb-item">Inicio</li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->

<!-- Modal novo contrato-->
<div class="modal fade" id="modalSaldos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="fechar">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 mb-2">
                    <div class="card bg-primary text-light text-center">
                        <div class="card-body">

                            <span class="fa-stack fa-4x">
                                <i class="fa fa-circle fa-stack-2x text-primary"></i>
                                <i class="fa fa-dollar fa-stack-1x fa-inverse"></i>
                            </span>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item text-muted small">
                                    <span class="float-left ">
                                        <label class="form-check-label">
                                            &nbsp;Retenções
                                        </label>
                                    </span>
                                    <span class="float-right"><strong>R$ 4.000,00</strong></span>
                                </li>
                                <li class="list-group-item text-muted small">
                                    <span class="float-left ">
                                        <label class="form-check-label">
                                            &nbsp;Liberações
                                        </label>
                                    </span>
                                    <span class="float-right"><strong>R$ 1.288,94</strong></span>
                                </li>

                                <li class="list-group-item text-center text-primary">
                                    <strong>
                                        Saldo das contas: R$ 0,00
                                    </strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div><!-- Modal -->

<div class="row">
    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
        <?php if ($nivel_acesso === ADMINISTRADOR) : ?>
            <a href="<?= APP_HTTP; ?>cadastro/clientes/index.php" class="clientes text-light ">
                <div class="card-box altura-card-botoes-grafico noradius noborder bg-default">
                    <i class="fa fa-file-text-o float-right text-white"></i>
                    <h6 class="text-white text-uppercase m-b-20">Clientes</h6>
                    <h1 class="m-b-20 text-white" id="quantidade-clientes"></h1>
                    <span class="text-white">&nbsp;</span>
                </div>
            </a>
        <?php elseif ($nivel_acesso === VISITANTE) : ?>
            <div class="card-box altura-card-botoes-grafico noradius noborder bg-default">
                <i class="fa fa-file-text-o float-right text-white"></i>
                <h6 class="text-white text-uppercase m-b-20">Empresas</h6>
                <h1 class="m-b-20 text-white" id="quantidade-empresas"></h1>
                <span class="text-white">&nbsp;</span>
            </div>
        <?php else : ?>
            <a href="<?= APP_HTTP; ?>cadastro/empresas/index.php" class="empresas text-light mao-link">
                <div class="card-box altura-card-botoes-grafico noradius noborder bg-default">
                    <i class="fa fa-file-text-o float-right text-white"></i>
                    <h6 class="text-white text-uppercase m-b-20">Empresas</h6>
                    <h1 class="m-b-20 text-white" id="quantidade-empresas"></h1>
                    <span class="text-white">&nbsp;</span>
                </div>
            </a>
        <?php endif; ?>
    </div>

    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
        <!-- Visivel para usuários administradores -->
        <?php if ($nivel_acesso == ADMINISTRADOR) : ?>
            <div class="card-box altura-card-botoes-grafico noradius noborder bg-warning">
                <i class="fa fa-bar-chart float-right text-white"></i>
                <h6 class="text-white text-uppercase m-b-20">Contratos</h6>
                <h1 class="m-b-20 text-white" id="quantidade-contratos"></h1>
                <span class="text-white">&nbsp;</span>
            </div>
        <?php elseif ($nivel_acesso === VISITANTE) : ?>
            <div class="card-box altura-card-botoes-grafico noradius noborder bg-warning">
                <i class="fa fa-bar-chart float-right text-white"></i>
                <h6 class="text-white text-uppercase m-b-20">Contratos</h6>
                <h1 class="m-b-20 text-white" id="quantidade-contratos"></h1>
                <span class="text-white">&nbsp;</span>
            </div>
        <?php else : ?>
            <a href="<?= APP_HTTP; ?>cadastro/contratos/index.php" class="empregados text-light ">
                <div class="card-box altura-card-botoes-grafico noradius noborder bg-warning">
                    <i class="fa fa-bar-chart float-right text-white"></i>
                    <h6 class="text-white text-uppercase m-b-20">Contratos</h6>
                    <h1 class="m-b-20 text-white" id="quantidade-contratos"></h1>
                    <span class="text-white">&nbsp;</span>
                </div>
            </a>
        <?php endif; ?>
    </div>

    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
        <?php if ($nivel_acesso == ADMINISTRADOR) : ?>
            <div class="card-box altura-card-botoes-grafico noradius noborder bg-info">
                <i class="fa fa-user-o float-right text-white"></i>
                <h6 class="text-white text-uppercase m-b-20">Empregados</h6>
                <h1 class="m-b-20 text-white" id="quantidade-empregados"></h1>
                <span class="text-white">&nbsp;</span>
            </div>
        <?php elseif ($nivel_acesso === VISITANTE) : ?>
            <div class="card-box altura-card-botoes-grafico noradius noborder bg-info">
                <i class="fa fa-user-o float-right text-white"></i>
                <h6 class="text-white text-uppercase m-b-20">Empregados</h6>
                <h1 class="m-b-20 text-white" id="quantidade-empregados"></h1>
                <span class="text-white">&nbsp;</span>
            </div>
        <?php else : ?>
            <a href="<?= APP_HTTP; ?>cadastro/empregados/index.php" class="empregados text-light">
                <div class="card-box altura-card-botoes-grafico noradius noborder bg-info">
                    <i class="fa fa-user-o float-right text-white"></i>
                    <h6 class="text-white text-uppercase m-b-20">Empregados</h6>
                    <h1 class="m-b-20 text-white" id="quantidade-empregados"></h1>
                    <span class="text-white">&nbsp;</span>
                </div>
            </a>
        <?php endif; ?>
    </div>

    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
        <div class="mao-link">
            <div class="card-box altura-card-botoes-grafico noradius noborder bg-danger">
                <i class="fa fa-dollar float-right text-white"></i>
                <h7 class="text-white text-uppercase m-b-20">Saldo de Contas</h7>
                <h2 class="m-b-20 text-white" id="quantidade-saldoContas"></h2>
                <h12 class="text-white">
                    <table>
                        <tr>
                            <td class="text-left">Retenções</td>
                            <td class="text-right"><span id="quantidade-retencoes">R$</span></td>
                        </tr>
                        <tr>
                            <td class="text-left">Liberações</td>
                            <td class="text-right"><span id="quantidade-liberacoes">R$</span></td>
                        </tr>
                    </table>
                </h12>
            </div>
        </div>
    </div>
</div>
<!-- end row -->

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
        <div class="card mb-3">
            <div class="card-header">
                <?php if ($nivel_acesso == ADMINISTRADOR) : ?>
                    <h3><i class="fa fa-line-chart"></i> Pagamentos no mes</h3>
                    Apresenta os pagamentos previstos / Realizados ao longo dos meses
                <?php else : ?>
                    <h3><i class="fa fa-line-chart"></i> Retenção / Liberação</h3>
                    Apresenta os valores de retenções / Liberações do mes
                <?php endif; ?>
            </div>

            <div class="card-body">
                <canvas id="lineChart"></canvas>
            </div>
        </div><!-- end card-->
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3">
        <div class="card mb-3">
            <div class="card-header botaoSaldos">
                <?php if ($nivel_acesso == ADMINISTRADOR) : ?>
                    <h3><i class="fa fa-bar-chart-o"></i> Contratos / Categorias</h3>
                    Apresenta a quantidade de contrato por categoria de cliente
                <?php else : ?>
                    <h3><i class="fa fa-line-chart"></i> Retenções</h3>
                    Apresenta os saldos
                <?php endif; ?>
            </div>

            <div class="card-body">
                <canvas id="pieChart"></canvas>
            </div>
        </div><!-- end card-->
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3">
        <div class="card mb-3">
            <div class="card-header">
                <?php if ($nivel_acesso == ADMINISTRADOR) : ?>
                    <h3><i class="fa fa-bar-chart-o"></i> Empregados / Categorias</h3>
                    Apresenta a quantidade de empregados por categoria de clientes.
                <?php else : ?>
                    <h3><i class="fa fa-line-chart"></i> Liberações</h3>
                    Apresenta as liberações mensais
                <?php endif; ?>
            </div>

            <div class="card-body">
                <canvas id="doughnutChart"></canvas>
            </div>
        </div><!-- end card-->
    </div>

</div>
<!-- end row -->

<?php $template->fimConteudo() ?>
<?php $template->renderiza() ?>