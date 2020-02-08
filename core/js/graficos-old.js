var graficos = {
  SVGNS: 'http://www.w3.org/2000/svg',
};
graficos.pizza = function(svg, dados, cores) {
  svg.viewBox.baseVal.width = 1;
  svg.viewBox.baseVal.height = 1;
  var W = svg.viewBox.baseVal.width;
  var H = svg.viewBox.baseVal.height;
  var r = W / 2;
  var paths = svg.querySelectorAll('path');
  for (var i = 0; i < paths.length; i++) {
    svg.removeChild(paths[i]);
  }
  var total = 0;
  for (var i = 0; i < dados.length; i++) {
    total += dados[i];
  }
  var a, x = W, y = H / 2, p, d, sa = 0, flags;
  for (var i = 0; i < dados.length; i++) {
    a = 2 * Math.PI * dados[i] / total;
    if (dados.length == 1) {
      a *= 0.99999;
    }
    sa += a;
    x2 = r + r * Math.cos(sa);
    y2 = r - r * Math.sin(sa);
    if (a > Math.PI) {
      flags = '0 1';
    } else {
      flags = '0 0';
    }
    p = document.createElementNS(this.SVGNS, 'path');
    p.setAttribute('fill', cores[i % cores.length]);
    d = 'M ' + r + ' ' + r + ' L ' + x + ' ' + y + ' A ' + r + ' ' + r + ' ' + flags + ' 0 ' + x2 + ' ' + y2 + ' Z';
    p.setAttribute('d', d);
    svg.appendChild(p);
    x = x2;
    y = y2;
  }
};
graficos.barra = function(g, d, aoselecionar) {
  var b;
  g.classList.add('grafico-barras');
  g.innerHTML = '';
  var maior = 0;
  for (var i = 0; i < d.length; i++) {
    if (d[i].valor > maior) {
      maior = d[i].valor;
    }
  }
  for (var i = 0; i < d.length; i++) {
    b = document.createElement('div');
    b.className = 'barra';
    b.style.width = (100 * d[i].valor / maior) + '%';
    b.innerHTML = d[i].rotulo;
    b.setAttribute('data-rotulo', d[i].rotulo);
    b.setAttribute('data-valor', d[i].valor);
    if (d[i].atributos) {
      for (k in d[i].atributos) {
        b.setAttribute('data-' + k, d[i].atributos[k]);
      }
    }
    if (aoselecionar) {
      b.onclick = function() {
        aoselecionar(this);
      };
    }
    g.appendChild(b);
  }
};