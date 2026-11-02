-- TAG Migration v3.13.21 -- Campo Fornecedor no Lancamento de Estoque (merenda)
--
-- Adiciona o campo "Fornecedor" ao item de estoque (food_inventory),
-- preenchido no momento do lancamento de estoque, para identificar a
-- origem do produto (fabricante/fornecedor, ou "Agricultura Familiar"
-- para frutas/verduras/legumes).

ALTER TABLE food_inventory
    ADD COLUMN supplier VARCHAR(255) NULL AFTER food_fk;
