ALTER TABLE course_class MODIFY COLUMN methodology TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL;

ALTER TABLE course_class DEFAULT CHARSET=utf8mb4;
