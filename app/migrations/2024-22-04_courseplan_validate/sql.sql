ALTER TABLE course_plan ADD COLUMN situation ENUM('APROVADO', 'PENDENTE', 'REJEITADO') NOT NULL;

ALTER TABLE course_plan ADD COLUMN start_date DATE NOT NULL;

ALTER TABLE course_plan ADD COLUMN observation VARCHAR(500);

INSERT INTO `demo.tag.ong.br`.auth_item (name,`type`,`data`) VALUES ('coordinator',2,'N;');
