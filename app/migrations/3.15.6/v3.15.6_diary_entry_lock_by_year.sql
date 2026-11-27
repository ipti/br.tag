-- Preserve regras existentes para os anos com turmas e para o ano corrente.
-- A tabela antiga fica disponível para auditoria e recuperação.
CREATE TABLE IF NOT EXISTS diary_entry_lock_year (
    id INT NOT NULL AUTO_INCREMENT,
    school_inep_fk VARCHAR(8) NOT NULL,
    school_year INT NOT NULL,
    classroom_fk INT NOT NULL DEFAULT 0,
    entry_type VARCHAR(16) NOT NULL,
    locked TINYINT(1) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY diary_entry_lock_year_scope (school_inep_fk, school_year, classroom_fk, entry_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS diary_entry_lock_year_migration (
    id TINYINT NOT NULL PRIMARY KEY
) ENGINE=InnoDB;

INSERT IGNORE INTO diary_entry_lock_year (school_inep_fk, school_year, classroom_fk, entry_type, locked)
SELECT DISTINCT old.school_inep_fk, c.school_year, 0, old.entry_type, old.locked
FROM diary_entry_lock old
JOIN classroom c ON c.school_inep_fk = old.school_inep_fk
WHERE old.classroom_fk = 0 AND c.school_year IS NOT NULL
AND NOT EXISTS (SELECT 1 FROM diary_entry_lock_year_migration WHERE id = 1);

INSERT IGNORE INTO diary_entry_lock_year (school_inep_fk, school_year, classroom_fk, entry_type, locked)
SELECT old.school_inep_fk, YEAR(CURDATE()), 0, old.entry_type, old.locked
FROM diary_entry_lock old
WHERE old.classroom_fk = 0
AND NOT EXISTS (SELECT 1 FROM diary_entry_lock_year_migration WHERE id = 1);

INSERT IGNORE INTO diary_entry_lock_year (school_inep_fk, school_year, classroom_fk, entry_type, locked)
SELECT old.school_inep_fk, c.school_year, old.classroom_fk, old.entry_type, old.locked
FROM diary_entry_lock old
JOIN classroom c ON c.id = old.classroom_fk AND c.school_inep_fk = old.school_inep_fk
WHERE old.classroom_fk <> 0 AND c.school_year IS NOT NULL
AND NOT EXISTS (SELECT 1 FROM diary_entry_lock_year_migration WHERE id = 1);

INSERT IGNORE INTO diary_entry_lock_year_migration (id) VALUES (1);
