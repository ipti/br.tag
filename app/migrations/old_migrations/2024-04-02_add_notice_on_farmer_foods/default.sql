ALTER TABLE farmer_foods ADD foodNotice_fk int(11) NULL;

ALTER TABLE farmer_foods
ADD CONSTRAINT fk_farmer_foods_food_notice
FOREIGN KEY (foodNotice_fk) REFERENCES food_notice(id);

ALTER TABLE food_request add farmer_fk int(11) NOT NULL;

ALTER TABLE food_request
ADD CONSTRAINT fk_food_request_farmer
FOREIGN KEY (farmer_fk) REFERENCES farmer_register(id);

ALTER TABLE food_notice_item ADD foodNotice_fk int(11) NULL;

ALTER TABLE food_notice_item
ADD CONSTRAINT fk_food_notice_item_food_notice
FOREIGN KEY (foodNotice_fk) REFERENCES food_notice(id);

DROP TABLE food_notice_vs_food_notice_item;
