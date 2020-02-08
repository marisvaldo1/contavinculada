<div class="left main-sidebar">
    <div class="sidebar-inner leftscroll">
        <div id="sidebar-menu">

            <ul>
                <?php
                if (PAGINA == "/app/inicio/index.php")
                    $itemAtivo = "class='active'";
                else
                    $itemAtivo = "class=''";
                ?>
                <li class="submenu">
                    <a <?= $itemAtivo; ?> href="<?= APP_HTTP; ?>"><i class="fa fa-fw fa-bars"></i><span> Inicial </span> </a>
                </li>

                <?php
                if (    (PAGINA == "/app/cadastro/clientes/index.php") ||
                        (PAGINA == "/app/cadastro/contratos/index.php") ||
                        (PAGINA == "/app/cadastro/encargos/index.php") ||
                        (PAGINA == "/app/cadastro/indices_retencao/index.php") ||
                        (PAGINA == "/app/cadastro/empregados/index.php") ||
                        (PAGINA == "/app/cadastro/contas_correntes/index.php"))
                    $itemAtivo = "class='active'";
                else
                    $itemAtivo = "class=''";
                ?>

                <li class="submenu">
                    <a <?= $itemAtivo; ?> href="#"><i class="fa fa-fw fa-file-text-o"></i> <span> Cadastrosxxxxxxxxxx </span> <span class="menu-arrow"></span></a>                    
                    <ul class="list-unstyled">
                        <li class="submenu">
                            <a <?= $itemAtivo; ?> href="#"><span>Tabelas Básicas</span> <span class="menu-arrow"></span> </a>
                            <ul style="">
                                <li><a href="<?= APP_HTTP; ?>cadastro/clientes/index.php"><span>Clientes</span></a></li>
                                <li><a href="<?= APP_HTTP; ?>cadastro/contratos/index.php"><span>Contratos</span></a></li>
                                <li><a href="<?= APP_HTTP; ?>cadastro/encargos/index.php"><span>Encargos Sociais</span></a></li>
                                <li><a href="<?= APP_HTTP; ?>cadastro/indices_retencao/index.php"><span>Índices de Retenção</span></a></li>
                                <li><a href="<?= APP_HTTP; ?>cadastro/empregados/index.php"><span>Empregados</span></a></li>
                                <li><a href="<?= APP_HTTP; ?>cadastro/contas_correntes/index.php"><span>Contas Correntes</span></a></li>
                            </ul>
                        </li>                                
                        <li class="submenu">
                            <a href="#"><span>Controle</span> <span class="menu-arrow"></span> </a>
                            <ul style="">
                                <li><a href="<?= APP_HTTP; ?>cadastro/usuarios/index.php"><span>Usuários</span></a></li>
                                <li><a href="<?= APP_HTTP; ?>cadastro/empresas/index.php"><span>Empresas</span></a></li>
                            </ul>
                        </li>                                
                    </ul>
                </li>

                <div class="clearfix"></div>
                <li class="submenu">
                    <a <?= $itemAtivo; ?> href="#"><i class="fa fa-fw fa-table"></i> <span> Controles de Acesso </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="<?= APP_HTTP; ?>controle_acesso/definicao_acesso/index.php">Definições de Acesso</a></li>
                        <li><a href="<?= APP_HTTP; ?>controle_acesso/log_acesso/index.php"><span>Logs de Acesso e Ações</span></a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a <?= $itemAtivo; ?> href="#"><i class="fa fa-fw fa-tv"></i> <span> Conta Corrente </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="#">Lançamentos</a></li>
                        <li><a href="#">Captura Dados</a></li>
                        <li><a href="#">Extratos</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a <?= $itemAtivo; ?> href="#"><i class="fa fa-fw fa-file-text-o"></i> <span> Relatórios Gerenciais </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="#">Retenções por Contrato</a></li>
                        <li><a href="#">Liberações por Contrato</a></li>
                        <li><a href="#">Retenções por Empregado</a></li>
                        <li><a href="#">Liberações por Empregado</a></li>
                        <li><a href="#">Extrato por Empregado</a></li>
                    </ul>
                </li>
            </ul>

        </div>

        <div class="clearfix"></div>

    </div>

</div>