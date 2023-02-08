CREATE TABLE attendance (
     id_attendance INT NOT NULL PRIMARY KEY,
     date DATE NOT NULL,
     local varchar(100) NOT NULL,
     professional_fk INT NOT NULL
);

CREATE TABLE professional (
     id_professional int not null PRIMARY key,
     cpf_professional varchar(14) not null,
     specialty varchar(100) not null,
     inep_id_fk varchar(8) not null,
     fundeb BOOLEAN NOT NULL
);


ALTER TABLE attendance CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE attendance ADD CONSTRAINT fk_professional_attendance FOREIGN KEY (professional_fk) REFERENCES professional(id_professional);

ALTER TABLE professional CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE professional ADD CONSTRAINT fk_schoolidentificationProfessional FOREIGN KEY (inep_id_fk) REFERENCES school_identification(inep_id);

ALTER TABLE `student_enrollment` ADD `date_cancellation_enrollment` DATE NULL DEFAULT NULL AFTER `status`;
ALTER TABLE `school_identification` ADD `number_ato` VARCHAR(30) NOT NULL AFTER `final_date`;
ALTER TABLE `lunch_menu` ADD `adjusted` TINYINT NOT NULL AFTER date;


INSERT INTO professional VALUES (1,'71685776035','Médico','28022041', 1);
INSERT INTO attendance VALUES (1, curdate(), 'Itabaiana', 1);

UPDATE student_identification
SET birthday = STR_TO_DATE(birthday, '%Y-%m-%d');