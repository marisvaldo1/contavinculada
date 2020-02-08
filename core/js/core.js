function query_string(objeto) {
  var qs = [];
  for (var chave in objeto) {
    qs.push(chave + '=' + encodeURIComponent(objeto[chave]));
  }
  return qs.join('&');
}

function ajax(url, campos, callback, metodo, progresso) {

  function query_string_elementos(elementos) {
    var dic = {};
    for (var i = 0; i < elementos.length; i++) {
      if (elementos[i].type != 'checkbox' && elementos[i].type != 'radio' || elementos[i].checked) {
        dic[elementos[i].name || elementos[i].id] = elementos[i].value;
      }
    }
    return query_string(dic);
  }

  var map_met = {
    'GET': 'GET',
    'get': 'GET',
    'G': 'GET',
    'g': 'GET',
    'POST': 'POST',
    'post': 'POST',
    'P': 'POST',
    'p': 'POST',
  };
  if (typeof map_met[metodo] != 'undefined') {
    metodo = map_met[metodo];
  } else {
    metodo = 'GET';
  }
  if (campos instanceof HTMLElement) {
    if (campos.tagName == 'FORM') {
      campos = query_string_elementos(campos.elements);
    } else {
      campos = query_string_elementos(campos.querySelectorAll('input, select, textarea'));
    }
  } else if (campos instanceof FormData) {
    metodo = 'POST';
  } else if (typeof campos == 'string') {
    //não faça nada, query_string supostamente está pronta. Exemplo: a=1&b=2.
  } else if (campos instanceof Object) {
    campos = query_string(campos);
  }
  var xhr = new XMLHttpRequest();
  if (metodo == 'POST') {
    xhr.open(metodo, url, true);
    if (!(campos instanceof FormData)) {
      xhr.setRequestHeader('content-type', 'application/x-www-form-urlencoded; charset=utf-8');
    }
  } else {
    if (campos) {
      url = url + '?' + campos;
    }
    xhr.open(metodo, url, true);
    xhr.setRequestHeader('content-type', 'text/html; charset=utf-8');
  }
  xhr.setRequestHeader('Cache-Control', 'no-store, no-cache, must-revalidate');
  xhr.onload = function() {
    if (callback) {
      callback(this.responseText, this.status);
    }
  };
  if (progresso) {
    if (xhr.upload) {
      xhr.upload.onprogress = function(e) {
        progresso(e);
      };
    }
  }
  xhr.send(campos);
  return xhr;
}

function delrow(linha) {
  linha.parentNode.parentNode.deleteRow(linha.rowIndex);
}

function xy(elemento) {
  var y = 0, x = 0;
  if (elemento.offsetParent) {
    do {
      y += elemento.offsetTop;
      x += elemento.offsetLeft;
    } while (elemento = elemento.offsetParent);
  }
  return {'x': x, 'y': y};
}

function foreach(nodeList, fun) {
  Array.prototype.forEach.call(nodeList, fun);
}

var historico = {};
historico.disabled = false;
historico.hash = function() {
  var hash = location.hash.replace('#', '');
  var arr_hash = hash.split('&');
  var obj_hash = {};
  var campo;
  for (var i = 0; i < arr_hash.length; i++) {
    if (arr_hash[i]) {
      campo = arr_hash[i].split('=');
      obj_hash[campo[0]] = decodeURIComponent(campo[1] || '');
    }
  }
  return obj_hash;
};
historico.load = function(funcao) {
  window.addEventListener('load', function() {
    historico.onload = true;
    historico.disabled = true;
    funcao(historico.hash());
    historico.disabled = false;
  });
  window.addEventListener('popstate', function() {
    historico.onload = false;
    historico.disabled = true;
    funcao(historico.hash());
    historico.disabled = false;
  });
};
historico.push = function(dados) {
  var hash;
  if (!this.disabled) {
    var arr_hash = [];
    for (var k in dados) {
      arr_hash.push(k + '=' + encodeURIComponent(dados[k]));
    }
    if (arr_hash.length) {
      hash = '#' + arr_hash.join('&');
    } else {
      hash = '';
    }
    if (hash != location.hash) {
      history.pushState(null, null, location.pathname + location.search + hash);
    }
  }
};

function is_touch() {
  return 'ontouchstart' in window        // works on most browsers
      || navigator.maxTouchPoints;       // works on IE10/11 and Surface
}

function is_visible(el) {
  return (el.offsetWidth > 0 && el.offsetHeight > 0);
}

function is_inviewport(el) {
  var rect = el.getBoundingClientRect();
  var A = rect.width * rect.height;
  return A && rect.top >= 0 && rect.bottom <= window.innerHeight && rect.left >= 0 && rect.right <= window.innerWidth;
}

function is_scrollable(el) {
  return el.scrollHeight > el.offsetHeight || el.scrollWidth > el.offsetWidth;
}

function is_data(strdata) {

  function e_data(dia, mes, ano) {
    dia = parseFloat(dia);
    mes = parseFloat(mes) - 1;
    ano = parseFloat(ano);
    var data = new Date(ano, mes, dia, 12, 0, 0);
    var d = data.getDate();
    var m = data.getMonth();
    var a = data.getFullYear();
    return (d == dia && m == mes && a == ano);
  }

  var partes = strdata.split('/');
  if (partes.length == 3) {
    return e_data(partes[0], partes[1], partes[2]);
  }
  return false;
}

function cria_data(strdata) {
  if (!is_data(strdata)) {
    throw 'Formato de data inválido para criação.';
  }
  var d = new Date();
  var partes = strdata.split('/');
  d.setDate(parseFloat(partes[0]));
  d.setMonth(parseFloat(partes[1]) - 1);
  d.setFullYear(parseFloat(partes[2]));
  d.setHours(0);
  d.setMinutes(0);
  d.setSeconds(0);
  d.setMilliseconds(0);
  return d;
}

function date_diff(d1, d2) {
  var diferenca = d1.getTime() - d2.getTime();
  return Math.floor(diferenca / 86400000);
}

function gera_token() {
  return Math.random().toString(16).substr(2);
}

var QUERY_SELECTOR_CAMPOS = 'input[type=text], input[type=hidden], input[type=email], input[type=password], ' +
    'input[type=checkbox], input[type=radio], select, textarea, input[type=tel], input[type=range]';

var api = {};
api.cep = function(cep, retorno) {
  ajax(SITE + 'core/api/cep.php', {'cep': cep}, function(json) {
    retorno(JSON.parse(json));
  });
};
api.unidade_negocio = {};
api.unidade_negocio.pesquisa = function(t, retorno, opcoes) {
  var g = {
    t: t,
    apenas_superiores: opcoes.apenas_superiores ? '1' : '0',
  };
  if (opcoes) {
    if (opcoes.tipos) {
      g.tipos = opcoes.tipos;
    }
  }
  ajax(SITE + 'core/api/unidade_negocio/pesquisa.php', g, function(json) {
    retorno(JSON.parse(json));
  });
};

api.unidade_negocio.pesquisa_mcu = function(mcu, retorno) {
  ajax(SITE + 'core/api/unidade_negocio/pesquisa_mcu.php', {'mcu': mcu}, function(json) {
    retorno(JSON.parse(json));
  });
};
var DataHora = {
  hoje: function() {
    var d = new Date();
    var dia = d.getDate();
    var mes = d.getMonth() + 1;
    var ano = d.getFullYear();
    if (dia < 10) {
      dia = '0' + dia;
    }
    if (mes < 10) {
      mes = '0' + mes;
    }
    return dia + '/' + mes + '/' + ano;
  },
};
var Numeros = {
  formata: function(numero, casas, separador) {
    var retorno, negativo = false;
    if (numero === '' || numero === null) {
      return null;
    }
    numero = parseFloat(numero);
    if (numero < 0) {
      negativo = true;
      numero = Math.abs(numero);
    }
    if (isNaN(numero)) {
      throw 'Número precisa ser real para ser formatado.';
    }
    if (casas === undefined) {
      casas = 2;
    }
    var partes = numero.toFixed(casas).split('.');
    var parteInteira = partes[0];
    var parteDecimal = partes[1];
    if (separador) {
      var trincas = [];
      while (parteInteira.length > 3) {
        trincas.push(parteInteira.substr(parteInteira.length - 3));
        parteInteira = parteInteira.substring(0, parteInteira.length - 3);
      }
      if (parteInteira.length) {
        trincas.push(parteInteira);
      }
      trincas.reverse();
      retorno = trincas.join('.');
    } else {
      retorno = parteInteira;
    }
    if (casas) {
      retorno += ',' + parteDecimal;
    }
    if (negativo) {
      retorno = '-' + retorno;
    }
    return retorno;
  },
  moeda: function(numero) {
    return Numeros.formata(numero, 2, true);
  },
  real: function(strnumero) {
    strnumero = strnumero.toString();
    if (strnumero.indexOf('.') != -1 && strnumero.indexOf(',') != -1) {
      strnumero = strnumero.replace(/\./g, '').replace(',', '.');
    } else if (strnumero.indexOf(',') != -1) {
      strnumero = strnumero.replace(',', '.');
    }
    if (isNaN(strnumero)) {
      throw 'Número com formato incorreto para ser convertido em real.';
    }
    return parseFloat(strnumero);
  },
  eInteiro: function(numero) {
    return !isNaN(numero) &&
        parseInt(Number(numero)) == numero && !isNaN(parseInt(numero, 10));
  },
  novesFora: function(numero) {
    if (numero < 10) {
      return numero;
    }
    var texto = numero.toString();
    var soma = 0;
    for (var i = 0; i < texto.length; i++) {
      soma += parseInt(texto.charAt(i), 10);
    }
    return Numeros.novesFora(soma);
  },
};

var APICORREIOS = {
  unidadeNegocio: {
    orgaos: function(texto, callback) {
      var campos = {
        'o': texto,
      };
      ajax('http://apps2.correiosnet.int/mservice/orgaos', campos, function(res) {
        var r = JSON.parse(res);
        if (!r[0]) {
          return;
        }
        callback(r[0].Resultado);
      });
    },
  },
};
