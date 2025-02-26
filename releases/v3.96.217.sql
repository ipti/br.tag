-- app/migrations/2024-12-12_add_weight_final_recovery/2024-12-12_add_weight_final_recovery.sql
alter table grade_unity
 add column weight_final_media int(11),
 add column weight_final_recovery int(11);


-- app/migrations/2025-04-02_update_instructors_scholarity/sql.sql
UPDATE instructor_variable_data ivd
SET scholarity = 5
WHERE ivd.scholarity = 7;


-- app/migrations/2025-20-01_create_reader_user/2025-20-01_create_reader_user.sql
insert into auth_item (name, type) values ('reader', 2);


-- app/migrations/2025-2401_create_atand_update_at_on_log/2025-2401_create_atand_update_at_on_log.sql
ALTER TABLE log
ADD COLUMN created_at DATETIME DEFAULT NULL,
ADD COLUMN updated_at DATETIME DEFAULT NULL;
