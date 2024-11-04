ALTER TABLE class_contents DROP FOREIGN KEY class_contents_ibfk_1;
ALTER TABLE class_contents DROP FOREIGN KEY class_contents_ibfk_2;

ALTER TABLE class_contents ADD COLUMN day INT(11);
ALTER TABLE class_contents ADD COLUMN month INT(11);
ALTER TABLE class_contents ADD COLUMN year INT(11);
ALTER TABLE class_contents ADD COLUMN classroom_fk INT(11);
ALTER TABLE class_contents ADD COLUMN discipline_fk INT(11);

UPDATE class_contents cc
JOIN schedule s ON cc.schedule_fk = s.id
SET cc.day = s.day,
    cc.month = s.month,
    cc.year = s.year,
    cc.classroom_fk = s.classroom_fk,
    cc.discipline_fk = s.discipline_fk;
