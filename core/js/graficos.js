var graficos = {
  cria: function(tag) {
    return document.createElementNS('http://www.w3.org/2000/svg', tag);
  },
  barras: function(container, parametros, quantidadeDegraus, aoClicar, handlerHint, sobreBarra) {

    function posicionaHint(e) {
      if (e.clientX < window.innerWidth / 2) {
        hint.style.left = (e.clientX) + 'px';
      } else {
        hint.style.left = (e.clientX - hint.offsetWidth) + 'px';
      }
      if (e.clientY < window.innerHeight / 2) {
        hint.style.top = (e.clientY) + 'px';
      } else {
        hint.style.top = (e.clientY - hint.offsetHeight) + 'px';
      }
    }

    function resize() {
      degrau.style.paddingBottom = (legenda.offsetHeight) + 'px';
      degrau.style.paddingTop = (svg.getBoundingClientRect().height * MARGEM_SUPERIOR / ALTURA) + 'px';
    }

    var avaliacoes = parametros.avaliacoes;
    var avaliados = parametros.avaliados;
    var dados = parametros.dados;
    var LARGURA = 1000,
        ALTURA = 1000,
        MARGEM_ESQUERDA = 0,
        MARGEM_SUPERIOR = 25,
        LARGURA_UTIL = LARGURA - MARGEM_ESQUERDA,
        ALTURA_UTIL = ALTURA - MARGEM_SUPERIOR;
    CORES = [
      '#FFD400',
      '#00416B',
      '#0083CA',
      '#FFE600',
      '#FFC20E',
      '#CD992B',
      '#D1CCC7',
      '#BDB4AB',
    ];

    container.classList.add('grafico-barras');
    var principal,
        degrau,
        deg,
        containerSvg,
        svg = container.querySelector('svg'),
        painelAvaliados,
        eixoY,
        eixoX,
        YEixoX,
        rect,
        hint,
        barrasJaInseridas,
        degrausInseridos,
        legenda,
        itemLegenda,
        menor,
        maior,
        amplitude,
        amplitudeDegrau,
        larguraAvaliacoes,
        larguraBarras,
        altura,
        x,
        y,
        l,
        c,
        valor,
        info,
        linhaDegrau,
        alturaDegrau;
    if (!svg) {
      principal = document.createElement('div');
      principal.className = 'principal';
      container.appendChild(principal);
      degrau = document.createElement('div');
      degrau.className = 'degrau';
      principal.appendChild(degrau);
      containerSvg = document.createElement('div');
      containerSvg.className = 'container-svg';
      principal.appendChild(containerSvg);
      svg = graficos.cria('svg');
      svg.setAttribute('viewBox', '0 0 ' + LARGURA + ' ' + ALTURA + '');
      svg.setAttribute('preserveAspectRatio', 'none');
      containerSvg.appendChild(svg);
      eixoY = graficos.cria('line');
      eixoY.setAttribute('class', 'eixo-y');
      eixoY.setAttribute('x1', MARGEM_ESQUERDA);
      eixoY.setAttribute('y1', '0');
      eixoY.setAttribute('x2', MARGEM_ESQUERDA);
      eixoY.setAttribute('y2', ALTURA);
      svg.appendChild(eixoY);
      eixoX = graficos.cria('line');
      eixoX.setAttribute('class', 'eixo-x');
      eixoX.setAttribute('x1', MARGEM_ESQUERDA);
      eixoX.setAttribute('x2', LARGURA);
      svg.appendChild(eixoX);
      legenda = document.createElement('div');
      legenda.className = 'legenda';
      containerSvg.appendChild(legenda);
      painelAvaliados = document.createElement('div');
      painelAvaliados.className = 'painel-avaliados';
      principal.appendChild(painelAvaliados);
      window.addEventListener('resize', resize);
    } else {
      principal = container.querySelector('.principal');
      degrau = container.querySelector('.degrau');
      containerSvg = container.querySelector('.container-svg');
      eixoY = svg.querySelector('.eixo-y');
      eixoX = svg.querySelector('.eixo-x');
      legenda = container.querySelector('.legenda');
      painelAvaliados = container.querySelector('.painel-avaliados');
      barrasJaInseridas = svg.querySelectorAll('.barra');
      for (var i = 0; i < barrasJaInseridas.length; i++) {
        svg.removeChild(barrasJaInseridas[i]);
      }
      degrausInseridos = svg.querySelectorAll('.degrau');
      for (var i = 0; i < degrausInseridos.length; i++) {
        svg.removeChild(degrausInseridos[i]);
      }
    }
    hint = document.querySelector('.graficos-hint');
    if (!hint) {
      hint = document.createElement('div');
      hint.innerHTML = 'tem alguma coisa.';
      hint.className = 'graficos-hint';
      document.body.appendChild(hint);
    }
    legenda.innerHTML = '';
    painelAvaliados.innerHTML = '<table></table>';
    for (var i = 0; i < avaliacoes.length; i++) {
      itemLegenda = document.createElement('div');
      itemLegenda.textContent = avaliacoes[i];
      itemLegenda.style.flexBasis = 'calc(100% / ' + avaliacoes.length + ')';
      legenda.appendChild(itemLegenda);
    }
    for (var i = 0; i < dados.length; i++) {
      for (var j = 0; j < dados[i].length; j++) {
        valor = typeof dados[i][j] == 'number' ? dados[i][j] : dados[i][j]['valor'];
        if (menor == undefined) {
          menor = valor;
        } else {
          menor = Math.min(menor, valor);
        }
        if (maior == undefined) {
          maior = valor;
        } else {
          maior = Math.max(maior, valor);
        }
      }
    }
    if (menor <= 0 && maior <= 0) {
      amplitude = menor;
      YEixoX = MARGEM_SUPERIOR;
    } else if (menor >= 0 && maior >= 0) {
      amplitude = maior;
      YEixoX = ALTURA;
    } else {
      amplitude = maior - menor;
      YEixoX = ALTURA_UTIL * maior / amplitude + MARGEM_SUPERIOR;
    }
    amplitudeDegrau = amplitude / quantidadeDegraus;

    console.log(menor, maior, amplitude, amplitudeDegrau, YEixoX);

    eixoX.setAttribute('y1', YEixoX);
    eixoX.setAttribute('y2', YEixoX);
    larguraAvaliacoes = LARGURA_UTIL / avaliacoes.length;
    larguraBarras = larguraAvaliacoes / (dados.length + 1);
    for (var i = 0; i < dados.length; i++) {
      l = painelAvaliados.querySelector('table').insertRow(-1);
      c = l.insertCell(-1);
      c.innerHTML = '<div class="cor" style="background-color: ' + CORES[i % CORES.length] + '"></div>';
      c = l.insertCell(-1);
      c.textContent = avaliados[i];
      for (var j = 0; j < dados[i].length; j++) {
        if (typeof dados[i][j] == 'number') {
          valor = dados[i][j];
          info = {};
        } else {
          valor = dados[i][j]['valor'];
          info = dados[i][j];
        }
        x = MARGEM_ESQUERDA + j * larguraAvaliacoes + i * larguraBarras + larguraBarras / 2;
        altura = ALTURA_UTIL * Math.abs(valor / amplitude);
        if (valor >= 0) {
          y = YEixoX - altura;
        } else {
          y = YEixoX;
        }
        rect = graficos.cria('rect');
        rect.setAttribute('class', 'barra');
        rect.setAttribute('x', x);
        rect.setAttribute('y', y);
        rect.setAttribute('width', larguraBarras);
        rect.setAttribute('height', altura);
        rect.setAttribute('data-avaliacao', avaliacoes[j]);
        rect.setAttribute('data-avaliado', avaliados[i]);
        rect.setAttribute('data-valor', valor);
        rect.setAttribute('fill', CORES[i % CORES.length]);
        rect.info = info;
        if (aoClicar) {
          rect.onclick = function() {
            aoClicar(this);
          };
        }
        if (sobreBarra) {
          rect.addEventListener('mouseover', function() {
            sobreBarra(this);
          });
        }
        if (handlerHint) {
          rect.addEventListener('mouseover', function(e) {
            hint.classList.add('visivel');
            hint.innerHTML = handlerHint(this);
            posicionaHint(e);
          });
          rect.addEventListener('mousemove', function(e) {
            posicionaHint(e);
          });
          rect.addEventListener('mouseout', function() {
            hint.classList.remove('visivel');
          });
        }
        svg.appendChild(rect);
      }
    }
    alturaDegrau = ALTURA_UTIL / quantidadeDegraus;
    degrau.innerHTML = '';
    if (quantidadeDegraus) {
      for (var i = 0; i <= quantidadeDegraus; i++) {
        deg = document.createElement('div');
        if (maior > 0) {
          deg.textContent = Numeros.formata(maior - (i * amplitudeDegrau), 0, true);
        } else {
          deg.textContent = Numeros.formata(i * amplitudeDegrau, 0, true);
        }
        deg.style.height = 'calc(100%/ ' + quantidadeDegraus + ')';
        degrau.appendChild(deg);
        y = i * alturaDegrau + MARGEM_SUPERIOR;
        linhaDegrau = graficos.cria('line');
        linhaDegrau.setAttribute('class', 'degrau');
        linhaDegrau.setAttribute('x1', 0);
        linhaDegrau.setAttribute('y1', y);
        linhaDegrau.setAttribute('x2', LARGURA_UTIL);
        linhaDegrau.setAttribute('y2', y);
        svg.appendChild(linhaDegrau);
      }
    }
    resize();
  },
};