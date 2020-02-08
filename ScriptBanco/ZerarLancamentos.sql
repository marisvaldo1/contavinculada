-- Zerar lançamentos na produção

delete from contavinculada.lancamentos where id_cliente > 0
delete from contavinculada.contratos_empregados where id_cliente > 0
delete from contavinculada.contratos_encargos where id_cliente > 0

--Não excluir os contratos
-- delete from contavinculada.contratos where id_cliente > 0

delete from contavinculada.historico_captura where id_cliente > 0
