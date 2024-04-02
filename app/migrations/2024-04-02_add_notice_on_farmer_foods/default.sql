ALTER TABLE farmer_foods ADD foodNotice_fk int(11) NULL;

ALTER TABLE farmer_foods
ADD CONSTRAINT fk_farmer_foods_food_notice
FOREIGN KEY (foodNotice_fk) REFERENCES food_notice(id);
