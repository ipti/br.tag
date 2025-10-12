insert into auth_item (name, type, description, bizrule, data) values ('foodServiceWorker', 2, 'Merendeira', NULL, NULL);

CREATE TABLE IF NOT EXISTS student_IMC (
    id INT NOT NULL AUTO_INCREMENT,
    height FLOAT NOT NULL,
    weight FLOAT NOT NULL,
    IMC FLOAT NOT NULL,
    observations VARCHAR(500) NULL,
    student_fk INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    CONSTRAINT fk_student_IMC_student_identification
        FOREIGN KEY (student_fk)
        REFERENCES student_identification(id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
);

insert into auth_item (name, type, description, bizrule, data) values ('TASK_STUDENT_IMC', 1, 'Acesso ao módulo de Acompanhamento de Saúde', NULL, NULL);
insert into feature_flags (feature_name, active, updated_at) values ('TASK_STUDENT_IMC', 1, NOW());



ALTER TABLE student_disorder
ADD COLUMN iron_deficiency_anemia TINYINT NULL,
ADD COLUMN hypovitaminosis_a TINYINT NULL,
ADD COLUMN rickets TINYINT NULL,
ADD COLUMN scurvy TINYINT NULL,
ADD COLUMN iodine_deficiency TINYINT NULL,
ADD COLUMN protein_energy_malnutrition TINYINT NULL,

ADD COLUMN overweight TINYINT NULL,
ADD COLUMN obesity TINYINT NULL,
ADD COLUMN dyslipidemia TINYINT NULL,
ADD COLUMN hyperglycemia_prediabetes TINYINT NULL,
ADD COLUMN type2_diabetes_mellitus TINYINT NULL,

ADD COLUMN anorexia_nervosa TINYINT NULL,
ADD COLUMN bulimia_nervosa TINYINT NULL,
ADD COLUMN binge_eating_disorder TINYINT NULL,

ADD COLUMN lactose_intolerance TINYINT NULL,
ADD COLUMN celiac_disease TINYINT NULL,
ADD COLUMN food_allergies TINYINT NULL,

ADD COLUMN asthma TINYINT NULL,
ADD COLUMN chronic_bronchitis TINYINT NULL,
ADD COLUMN allergic_rhinitis TINYINT NULL,
ADD COLUMN chronic_sinusitis TINYINT NULL,

ADD COLUMN diabetes_mellitus TINYINT NULL,
ADD COLUMN hypothyroidism TINYINT NULL,
ADD COLUMN hyperthyroidism TINYINT NULL,
ADD COLUMN dyslipidemia_metabolic TINYINT NULL,

ADD COLUMN arterial_hypertension TINYINT NULL,
ADD COLUMN congenital_heart_disease TINYINT NULL,

ADD COLUMN chronic_gastritis TINYINT NULL,
ADD COLUMN gastroesophageal_reflux_disease TINYINT NULL,

ADD COLUMN epilepsy TINYINT NULL
