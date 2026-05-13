-- TAG Migration: Educacenso export aliases for 2026
-- Copies the 2025 layout as baseline and applies the export-specific 2026 shifts.

START TRANSACTION;

INSERT INTO edcenso_alias (register, corder, attr, cdesc, `default`, stable, `year`)
SELECT src.register, src.corder, src.attr, src.cdesc, src.`default`, src.stable, 2026
FROM edcenso_alias src
WHERE src.`year` = 2025
  AND NOT EXISTS (
      SELECT 1
      FROM edcenso_alias dst
      WHERE dst.`year` = 2026
        AND dst.register = src.register
        AND dst.corder = src.corder
  );

-- Registro 10, campo 115: one consolidated device-use field.
UPDATE edcenso_alias
SET attr = NULL,
    cdesc = 'Dispositivos usados pelos alunos para acesso a internet',
    `default` = NULL
WHERE `year` = 2026
  AND register = 10
  AND corder = 115;

SET @deleted_register10_device_field := (
    SELECT COUNT(*)
    FROM edcenso_alias
    WHERE `year` = 2026
      AND register = 10
      AND corder = 116
      AND attr = 'internet_access_connected_personaldevice'
);

DELETE FROM edcenso_alias
WHERE `year` = 2026
  AND register = 10
  AND corder = 116
  AND attr = 'internet_access_connected_personaldevice';

UPDATE edcenso_alias
SET corder = corder - @deleted_register10_device_field
WHERE `year` = 2026
  AND register = 10
  AND corder > 116
  AND @deleted_register10_device_field = 1;

-- Registro 10, campo 117: one consolidated local-network field.
UPDATE edcenso_alias
SET attr = NULL,
    cdesc = 'Rede local interligando computadores',
    `default` = NULL
WHERE `year` = 2026
  AND register = 10
  AND corder = 117;

SET @deleted_register10_network_field := (
    SELECT COUNT(*)
    FROM edcenso_alias
    WHERE `year` = 2026
      AND register = 10
      AND corder = 118
      AND attr = 'internet_access_local_wireless'
);

DELETE FROM edcenso_alias
WHERE `year` = 2026
  AND register = 10
  AND corder = 118
  AND attr = 'internet_access_local_wireless';

UPDATE edcenso_alias
SET corder = corder - @deleted_register10_network_field
WHERE `year` = 2026
  AND register = 10
  AND corder > 118
  AND @deleted_register10_network_field = 1;

-- Registro 20, field descriptions for the consolidated 2026 values.
UPDATE edcenso_alias
SET attr = NULL,
    cdesc = 'Tipo de turma',
    `default` = NULL
WHERE `year` = 2026
  AND register = 20
  AND corder = 14;

UPDATE edcenso_alias
SET attr = NULL,
    cdesc = 'Forma de organizacao da turma',
    `default` = NULL
WHERE `year` = 2026
  AND register = 20
  AND corder = 28;

-- Keep only the fields accepted by the 2026 export layout.
DELETE FROM edcenso_alias
WHERE `year` = 2026
  AND (
      (register = 0 AND corder > 53)
      OR (register = 10 AND corder > 182)
      OR (register = 20 AND corder > 66)
      OR (register IN (301, 302) AND corder > 108)
      OR (register = 40 AND corder > 7)
      OR (register = 50 AND corder > 38)
      OR (register = 60 AND corder > 33)
  );

COMMIT;
