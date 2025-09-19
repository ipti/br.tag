
DELETE from auth_item_child;

delete from auth_item WHERE `type` = 0;


CREATE TABLE IF NOT EXISTS feature_flags (
  feature_name VARCHAR(64) COLLATE utf8_unicode_ci NOT NULL PRIMARY KEY,
  active       TINYINT(1) NOT NULL DEFAULT 1,
  updated_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
                ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_feature_flags_auth_item
    FOREIGN KEY (feature_name)
    REFERENCES auth_item(name)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
