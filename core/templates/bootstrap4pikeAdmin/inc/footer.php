<footer class="footer">
    <span class="col-md-12 text-center text-footer" data-toggle="tooltip" data-placement="top"
          title="<?= e(Sistema::$versao); ?>" data-original-title="<?= e(Sistema::$versao); ?>">
        
        <?php
        //Se não estiver logado direciona para a tela de login
        if ($_SESSION["dados_usuario"]->getNivel_acesso() === '') {
            location(SITE + 'core/seguranca/sair.php');
        }

        if ($_SESSION["dados_usuario"]->getNivel_acesso() == ADMINISTRADOR)
            $nomeCliente = 'Administrador';
        else
            $nomeCliente = $_SESSION["dados_usuario"]->getNome_cliente();
        ?>
        <?= '<strong>' . $nomeCliente . '</strong> - Tempo de sessão: ' ?> (<span id="temporizador" class="temporizador"></span>)
    </span>

    <script>
        var tempo = new Number();
        // Tempo em segundos
        tempo = 2400;  //40min

        //function startCountdown() {
        var temporizadorLogin = () => {
            // Se o tempo não for zerado
            if ((tempo - 1) >= 0) {
                // Pega a parte inteira dos minutos
                var min = parseInt(tempo / 60);
                // Calcula os segundos restantes
                var seg = tempo % 60;

                // Formata o número menor que dez, ex: 08, 07, ...
                if (min < 10) {
                    min = "0" + min;
                    min = min.substr(0, 2);
                }

                if (seg <= 9) {
                    seg = "0" + seg;
                }

                // Cria a variável para formatar no estilo hora/cronômetro
                horaImprimivel = '00:' + min + ':' + seg;

                //JQuery pra setar o valor
                //$('.temporizador').html(horaImprimivel);
                document.getElementById('temporizador').innerHTML = horaImprimivel;

                // Define que a função será executada novamente em 1000ms = 1 segundo
                //setTimeout('temporizadorLogin()', 1000);

                // diminui o tempo
                tempo--;

                // Quando o contador chegar a zero faz esta ação
            } else {
                window.open(SITE + 'core/seguranca/sair.php', '_self');
                //location.href = SITE + 'core/seguranca/sair.php'
            }
        }

        // Chama a função ao carregar a tela
        setInterval(function () {
            temporizadorLogin();
        }, 1000);


    </script>    
</footer>

<!-- Modal Filtra dados relatórios-->
<div class="modal fade" id="modal-filtra-relatorios" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-full" role="document">
        <div class="modal-content bg-theme radius-default">
            <div class="modal-body">

                <footer class="footer">
                    <span class="text-center" data-toggle="tooltip" data-placement="top"
                          title="<?= e(Sistema::$versao); ?>" data-original-title="<?= e(Sistema::$versao); ?>">
                        <?= e(date('Y')); ?> - <?= e(Sistema::$nome); ?>
                        <?php
                        if ($_SESSION["dados_usuario"]->getNivel_acesso() == ADMINISTRADOR)
                            $nomeCliente = 'Administrador';
                        else
                            $nomeCliente = $_SESSION["dados_usuario"]->getNome_cliente();
                        ?>
                        - <?= $nomeCliente ?>
                    </span>
                </footer>

                <!-- Modal Filtra dados relatórios-->
                <div class="modal fade" id="modal-filtra-relatorios" tabindex="-1" role="dialog" aria-hidden="true">

                    <div class="modal-dialog modal-dialog-centered modal-full" role="document">
                        <div class="modal-content bg-theme radius-default">
                            <div class="modal-body">
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
                                                <h3><i class="fa fa-2x fa-address-card"></i> Definiçõa de filtro para relatórios</h3>
                                            </div>

                                            <div class="card-body">
                                                <div class="card-body">
                                                    <div class="form-row">
                                                        <div class="form-group col-md-3">
                                                            <label>Empresa</label>
                                                            <select id="select-empresa" class="form-control">
                                                                <option value="">Selecione</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="select-contrato">Contrato</label>
                                                            <select id="select-contrato" class="form-control">
                                                                <option value="">Selecione</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-1">
                                                            <label for="select-mes">Mês</label>
                                                            <select id="select-mes" class="form-control">
                                                                <option value="0">...</option>
                                                                <option value="01">Jan</option>
                                                                <option value="02">Fev</option>
                                                                <option value="03">Mar</option>
                                                                <option value="04">Abr</option>
                                                                <option value="05">Mai</option>
                                                                <option value="06">Jun</option>
                                                                <option value="07">Jul</option>
                                                                <option value="08">Ago</option>
                                                                <option value="09">Set</option>
                                                                <option value="10">Out</option>
                                                                <option value="11">Nov</option>
                                                                <option value="12">Dez</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-1">
                                                            <label for="select-mes">Ano</label>
                                                            <select id="select-ano" class="form-control">
                                                                <option value="0">...</option>
                                                                <option value="2019">2019</option>
                                                                <option value="2020">2020</option>
                                                                <option value="2021">2021</option>
                                                                <option value="2022">2022</option>
                                                                <option value="2023">2023</option>
                                                                <option value="2024">2024</option>
                                                                <option value="2025">2025</option>
                                                                <option value="2026">2025</option>
                                                                <option value="2027">2025</option>
                                                                <option value="2028">2025</option>
                                                                <option value="2029">2025</option>
                                                                <option value="2030">2025</option>
                                                            </select>
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
                                                                <button id="botao-filtrar" type="button" class="btn btn-primary text-light">Filtrar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <!-- Modal visualiza recibo pagamento-->

                <div class="divider"></div>

                <div class="row">
                    <div class="col-12 text-center align-items-center rounded-circle">
                        <div class="recibo-pagamento"></div>
                        <div class="div-recibo-pagamento"></div>
                    </div>
                </div>

                <div id="botoes-modal-relatorios" class="row">
                    <div class="col-12 text-center">

                        <span class="mostra-email">
                            <a id="botao-email" class="mao-link text-primary" data-toggle="tooltip" data-placement="bottom" title="Enviar Recibo por e-mail">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-envelope fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </span>

                        <span class="mostra-imprimir">
                            <a id="botao-imprimir" class="mao-link text-primary" data-toggle="tooltip" data-placement="bottom" title="Imprimir relatório">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-print fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </span>

                        <span class="mostra-ok">
                            <a id="botao-ok" class="btn btn-default btn-theme-primary btn-circle botao-ok" data-toggle="tooltip" data-placement="bottom" title="Concluir">
                                <i class="fa fa-check fa-2x text-light" aria-hidden="true"></i>
                            </a>
                        </span>

                        <span class="mostra-etiqueta">
                            <a id="botao-etiqueta" class="mao-link text-primary" data-toggle="tooltip" data-placement="bottom" title="Gerar PDF">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-ticket-alt fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </span>

                        <span class="mostra-declaracao-conteudo">
                            <a id="botao-declaracao-conteudo" class="mao-link text-primary" data-toggle="tooltip" data-placement="bottom" title="Imprimir Declaração de conteúdo">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-th-list fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </span>

                        <?php if (isMobile()): ?>
                            <span class="fechar-modal">
                                <a id="botao-fechar-modal" class="mao-link text-primary" data-toggle="tooltip" data-placement="bottom" title="Fechar" data-dismiss="modal">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-times fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                            </span>
                        <?php endif; ?>

                    </div>
                </div>

            </div>

        </div>

    </div>
</div>

<!-- Modal fim de sessão -->
<div class="modal fade" id="modal-fim-sessao" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Atenção</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Sua sessão expirou. Por favor efetue um novo login.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="logar">Ok</button>
            </div>
        </div>
    </div>
</div>
