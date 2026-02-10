-- Migration 3.8.0: Add school_room table and room_fk to classroom

-- Create school_room table for physical classroom spaces
CREATE TABLE school_room (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    school_inep_fk VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    name VARCHAR(100) NOT NULL,
    capacity INT(11) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_school_room_school FOREIGN KEY (school_inep_fk) 
        REFERENCES school_identification (inep_id) 
        ON UPDATE CASCADE 
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Add room_fk to classroom table to associate turmas with physical rooms
ALTER TABLE classroom 
ADD COLUMN room_fk INT(11) NULL AFTER school_inep_fk;

-- Add foreign key constraint
ALTER TABLE classroom 
ADD CONSTRAINT fk_classroom_room 
    FOREIGN KEY (room_fk) 
    REFERENCES school_room (id) 
    ON UPDATE CASCADE 
    ON DELETE SET NULL;
