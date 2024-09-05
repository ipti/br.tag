ALTER TABLE farmer_register ADD status enum('Inativo','Ativo') default 'Ativo' NULL;

ALTER TABLE food_notice ADD status enum('Inativo','Ativo') default 'Ativo' NULL;

ALTER TABLE food_notice ADD reference_id varchar(36) NULL;

ALTER TABLE food_notice ADD file_name varchar(100) NULL;
