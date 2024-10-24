ALTER TABLE class_contents DROP FOREIGN KEY class_contents_ibfk_1;
ALTER TABLE class_contents DROP FOREIGN KEY class_contents_ibfk_2;

ALTER TABLE class_contents ADD COLUMN day INT(11);
ALTER TABLE class_contents ADD COLUMN month INT(11);
ALTER TABLE class_contents ADD COLUMN year INT(11);
ALTER TABLE class_contents ADD COLUMN classroom_fk INT(11);
ALTER TABLE class_contents ADD COLUMN discipline_fk INT(11);

-- ALTER TABLE class_contents MODIFY schedule_fk INT(11) NULL;

-- ALTER TABLE class_contents
-- ADD CONSTRAINT class_contents_ibfk_1
-- FOREIGN KEY (schedule_fk) REFERENCES schedule(id)
-- ON DELETE SET NULL
-- ON UPDATE CASCADE;
