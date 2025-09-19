CREATE TABLE food_menu_vs_edcenso_stage_vs_modality (
    id INT AUTO_INCREMENT PRIMARY KEY,
    edcenso_stage_vs_modality_fk INT NOT NULL,
    food_menu_fk INT NOT NULL,
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL,
    FOREIGN KEY (edcenso_stage_vs_modality_fk) REFERENCES edcenso_stage_vs_modality(id),
    FOREIGN KEY (food_menu_fk) REFERENCES food_menu(id)
);

ALTER TABLE food_menu_vs_food_public_target
ADD COLUMN created_at DATETIME DEFAULT NULL,
ADD COLUMN updated_at DATETIME DEFAULT NULL;
