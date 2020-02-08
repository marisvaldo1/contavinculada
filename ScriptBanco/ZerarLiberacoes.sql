-- Zerar liberações

UPDATE lancamentos
SET 
liberacao_decimo_terceiro = 0, 
liberacao_ferias_abono = 0, 
liberacao_multa_FGTS = 0,
liberacao_impacto_encargos_13 = 0,
liberacao_impacto_ferias_abono = 0
WHERE id_cliente = 1
AND id_empresa = 2
AND id_contrato = 17
AND ano = 2019

