create table vaccine(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description text,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

insert into vaccine(name, description) values
('BCG ID', null),
('Hepatite B', null),
('Tríplice bacteriana (DTPw ou DTPa)', null),
('Haemophilus influenzae b', null),
('Poliomielite (vírus inativados)', null),
('Rotavírus', null),
('Pneumocócicas conjugadas', null),
('Meningocócicas conjugadas ACWY/C', null),
('Meningocócica B', null),
('Influenza (gripe)', null),
('Poliomielite oral (vírus vivos atenuados)', null),
('Febre amarela', null),
('Hepatite A', null),
('Tríplice viral (sarampo, caxumba e rubéola)', null),
('Varicela (catapora)', null);


create table student_vaccine(
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id int,
  vaccine_id int,
  FOREIGN KEY (vaccine_id) REFERENCES vaccine(id),
  FOREIGN KEY (student_id) REFERENCES student_identification(id)
);
