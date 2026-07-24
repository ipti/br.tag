-- TAG Migration v3.13.20 -- Altura do Acompanhamento de Saude em centimetros
--
-- O campo "Altura" do Acompanhamento de Saude (student_imc.height) era
-- armazenado em metros, como FLOAT (ex: 1.78), e exibido/editado com
-- casas decimais em todas as telas e relatorios.
--
-- A aplicacao passou a tratar esse campo em centimetros, como numero
-- inteiro (ex: 178), em todas as telas, relatorios e no calculo de IMC.
--
-- Este script converte os dados ja existentes (metros -> centimetros) e
-- ajusta o tipo da coluna para INT. O IMC ja calculado e gravado
-- anteriormente (peso / altura_em_metros^2) nao muda, pois so a
-- representacao da altura esta sendo convertida, nao o calculo historico.
--
-- Idempotente: nenhuma altura humana em metros passa de 3, entao o
-- "WHERE height <= 3" so converte quem ainda esta em metros (rodar de
-- novo nao multiplica os valores ja convertidos). O ALTER TABLE tambem e
-- idempotente por natureza (rodar num campo que ja e INT nao da erro).

UPDATE student_imc SET height = ROUND(height * 100) WHERE height <= 3;

ALTER TABLE student_imc MODIFY COLUMN height INT NOT NULL;
