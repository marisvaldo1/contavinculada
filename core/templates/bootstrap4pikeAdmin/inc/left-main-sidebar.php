<?php 

if(!isset($_SESSION["dados_usuario"])){ 
    location(SITE . 'app/login/index.php');
}

$nivel_acesso = $_SESSION["dados_usuario"]->getNivel_acesso(); ?>

<form id="form-menu">
    <div class="left main-sidebar">
        <div class="sidebar-inner leftscroll">
            <div id="sidebar-menu">
                <ul>
                    <li class="submenu">
                        <a class="inicial" href="<?= APP_HTTP . '/index.php'; ?>"><i class="fa fa-fw fa-bars inicial"></i><span> Inicial </span> </a>
                    </li>

                    <!-- Visivel para todos os usuários menos visitantes -->
                    <?php if ($nivel_acesso !== VISITANTE): ?>
                        <li class="submenu">
                            <a class="cadastros" href="#"><i class="fa fa-fw fa-file-text-o"></i><span> Cadastros </span><span class="menu-arrow"></span></a>
                            <ul class="submenu">

                                <!-- Visivel para todos os usuários menos administradores -->
                                <?php if ($nivel_acesso !== ADMINISTRADOR): ?>
                                    <li class="empresas"><a href="<?= APP_HTTP; ?>cadastro/empresas/index.php"><span>Empresas</span></a></li>
                                    <li class="cargos"><a href="<?= APP_HTTP; ?>cadastro/cargos/index.php"><span>Cargos</span></a></li>
                                    <li class="empregados"><a href="<?= APP_HTTP; ?>cadastro/empregados/index.php"><span>Empregados</span></a></li>
                                    <li class="encargos-sociais"><a href="<?= APP_HTTP; ?>cadastro/encargos/index.php"><span>Encargos Sociais</span></a></li>
                                <?php endif; ?>

                                <!-- Visivel somente para administradores -->
                                <?php if ($nivel_acesso === ADMINISTRADOR): ?>
                                    <li class="categorias"><a href="<?= APP_HTTP; ?>cadastro/categorias/index.php"><span>Categorias de clientes</span></a></li>
                                    <li class="clientes"><a href="<?= APP_HTTP; ?>cadastro/clientes/index.php"><span>Clientes</span></a></li>
                                    <li class="usuarios"><a href="<?= APP_HTTP; ?>cadastro/usuarios/index.php"><span>Usuários</span></a></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <!-- Visivel somente para administradores -->
                    <?php if ($nivel_acesso === ADMINISTRADOR): ?>
                        <li class="submenu">
                            <a class="controle-contratos" href="#"><i class="fa fa-fw fa-table"></i> <span> Controle de Contratos </span> <span class="menu-arrow"></span></a>
                            <ul class="list-unstyled">
                                <li class="contrato_sistema"><a href="<?= APP_HTTP; ?>controle_contrato/contrato_sistema/index.php">Contratos</a></li>
                                <li class="pagamento"><a href="<?= APP_HTTP; ?>controle_contrato/pagamento/index.php">Pagamentos</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <!-- Visivel para todos os usuários menos administradores e visitantes -->
                    <?php if ($nivel_acesso !== ADMINISTRADOR && $nivel_acesso !== VISITANTE): ?>
                        <li class="contratos">
                            <a href="<?= APP_HTTP; ?>cadastro/contratos/index.php"><i class="fa fa-fw fa-building-o"></i><span>Contratos</span></a>
                        </li>
                    <?php endif; ?>

                    <!-- Visivel somente para o usuário com perfil de cliente (Administrador do contrato) -->
                    <?php if ($nivel_acesso === CLIENTE): ?>
                        <li class="submenu">
                            <a class="controle-acesso" href="#"><i class="fa fa-fw fa-table"></i> <span> Controles de Acesso </span> <span class="menu-arrow"></span></a>
                            <ul class="list-unstyled">
                                <li class="definicao-acesso"><a href="<?= APP_HTTP; ?>controle_acesso/definicao_acesso/index.php">Definições de Acesso</a></li>
                                <li class="log-acesso"><a href="<?= APP_HTTP; ?>controle_acesso/log_acesso/index.php"><span>Log de Acessos</span></a></li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <!-- Visivel para todos os usuários menos administradores -->
                    <?php if ($nivel_acesso !== ADMINISTRADOR): ?>
                        <?php if ($nivel_acesso !== VISITANTE): ?>
                            <li class="submenu">
                                <a class="conta-corrente" href="#"><i class="fa fa-fw fa-tv"></i> <span> Conta Corrente </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <!--<li class="captura-dados"><a href="<?= APP_HTTP; ?>conta_corrente/captura-dados/index.php">Captura Dados</a></li>-->

                                    <li class="submenu">
                                        <!-- <a class="verbas" < ?= $itemAtivo; ?> href="#"><span>Verbas</span> <span class="menu-arrow"></span> </a> -->
                                        <a class="verbas" href="#"><span>Verbas</span> <span class="menu-arrow"></span> </a>
                                                                            
                                        <ul style="">
                                            <li class="reter-verbas"><a href="<?= APP_HTTP; ?>conta_corrente/captura-dados/index.php"><span>Reter Verbas</span></a></li>
                                            <li class="liberar-verbas"><a href="<?= APP_HTTP; ?>conta_corrente/liberar-verbas/index.php"><span>Liberar Verbas</span></a></li>
                                            <!--<li class="excluir-verbas"><a href="<?= APP_HTTP; ?>conta_corrente/excluir-verbas/index.php"><span>Excluir Verbas</span></a></li>-->
                                            <li class="historico-captura"><a href="<?= APP_HTTP; ?>conta_corrente/historico-captura/index.php"><span>Histórico Captura</span></a></li>;
                                        </ul>
                                    </li>                                


                                    <li class="lancamentos"><a href="<?= APP_HTTP; ?>conta_corrente/lancamentos/index.php">Lançamentos</a></li>
                                    <li class="extratos"><a href="<?= APP_HTTP; ?>conta_corrente/extrato/index.php">Extratos</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>

                        <li class="submenu">
                            <a class="relatorios-gerenciais" href="#"><i class="fa fa-fw fa-file-text-o"></i> <span> Relatórios Gerenciais </span> <span class="menu-arrow"></span></a>
                            <ul class="list-unstyled">
                                <li class="retencoes-contrato"><a href="<?= APP_HTTP; ?>relatorios-gerenciais/rel_retencoes_contrato/index.php">Retenções</a></li>
                                <li class="liberacoes-contrato"><a href="<?= APP_HTTP; ?>relatorios-gerenciais/rel_liberacoes_contrato/index.php">Liberações</a></li>
                                <li class="rel_extrato"><a href="<?= APP_HTTP; ?>relatorios-gerenciais/rel_extrato/index.php">Extrato</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if ($nivel_acesso === ADMINISTRADOR): ?>
                        <li class="submenu">
                            <a class="ferramentas" href="#"><i class="fa fa-fw fa-tv"></i> <span> Ferramentas </span> <span class="menu-arrow"></span></a>
                            <ul class="list-unstyled">

                                <li class="monitoramento"><a href="<?= APP_HTTP; ?>ferramentas/monitoramento_usuario/index.php">Monitoramento Usuários</a></li>
                                <!--<li class="limpa_historico"><a href="<?= APP_HTTP; ?>ferramentas/limpar/historico/index.php">Limpar Histórico Captura</a></li>;;;-->
                            </ul>
                        </li>

                    <?php endif; ?>

                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</form>