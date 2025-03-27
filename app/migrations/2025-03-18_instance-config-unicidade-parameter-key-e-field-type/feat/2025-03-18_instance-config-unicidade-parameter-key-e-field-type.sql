
ALTER TABLE instance_config  MODIFY COLUMN parameter_key ENUM(
    'FEAT_DASHBOARD_POWER',
    'FEAT_FOOD',
    'FEAT_FREQ_CLASSCONT',
    'FEAT_GRADES',
    'FEAT_GRADESRELEASE',
    'FEAT_REPORTCARD',
    'FEAT_SAGRES_STATUS_ENROL',
    'FEAT_SEDSP',
    'HAS_INDIVIDUALRECORD',
    'VHA'
) NOT NULL;

ALTER TABLE instance_config
ADD COLUMN field_type ENUM('string', 'num', 'bool');

UPDATE instance_config
SET field_type = CASE
    WHEN parameter_key = 'VHA' THEN 'num'
    WHEN parameter_key = 'FEAT_GRADES' THEN 'bool'
    WHEN parameter_key = 'FEAT_SEDSP' THEN 'bool'
    WHEN parameter_key = 'FEAT_GRADESRELEASE' THEN 'bool'
    WHEN parameter_key = 'FEAT_FOOD' THEN 'bool'
    WHEN parameter_key = 'FEAT_REPORTCARD' THEN 'bool'
    WHEN parameter_key = 'FEAT_FREQ_CLASSCONT' THEN 'bool'
    WHEN parameter_key = 'FEAT_SAGRES_STATUS_ENROL' THEN 'bool'
    WHEN parameter_key = 'HAS_INDIVIDUALRECORD' THEN 'bool'
    WHEN parameter_key = 'FEAT_DASHBOARD_POWER' THEN 'bool'
    ELSE 'string'
END;


DELETE ic
FROM instance_config ic
JOIN (
    SELECT MIN(id) AS min_id, parameter_key
    FROM instance_config
    GROUP BY parameter_key
) AS subquery ON ic.parameter_key = subquery.parameter_key
WHERE ic.id != subquery.min_id;

ALTER TABLE instance_config
ADD CONSTRAINT unique_parameter_key UNIQUE (parameter_key)

