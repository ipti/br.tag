SELECT Count(*)
INTO @exists
FROM information_schema.tables 
WHERE table_type = 'BASE TABLE'
    AND table_name = 'authassignment';

SET @query = If(@exists>0,
    'ALTER TABLE `authassignment` 
	RENAME TO  `AuthAssignment` ;
	ALTER TABLE `authitem` 
	RENAME TO  `AuthItem` ;
	ALTER TABLE `authitemchild` 
	RENAME TO  `AuthItemChild`;',
    'SELECT \'nothing to rename\' status');

PREPARE stmt FROM @query;

EXECUTE stmt;