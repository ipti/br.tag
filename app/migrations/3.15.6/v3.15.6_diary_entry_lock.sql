-- Ausência de regra significa lançamento liberado. A regra da turma tem
-- precedência sobre a regra da escola e novas turmas herdam a escola.
CREATE TABLE IF NOT EXISTS diary_entry_lock (
    id INT NOT NULL AUTO_INCREMENT,
    school_inep_fk VARCHAR(8) NOT NULL,
    classroom_fk INT NOT NULL DEFAULT 0,
    entry_type VARCHAR(16) NOT NULL,
    locked TINYINT(1) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY diary_entry_lock_scope (school_inep_fk, classroom_fk, entry_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
