ALTER TABLE food_inventory ADD COLUMN previous_amount float NULL;

ALTER TABLE food_inventory_received ADD COLUMN expiration_date date NULL;