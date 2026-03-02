<?php
require_once('app/yiic.php');
$content = Yii::app()->db->createCommand('SELECT logo_file_content FROM school_identification WHERE logo_file_name IS NOT NULL LIMIT 1')->queryScalar();
var_dump(is_resource($content) ? 'stream' : substr($content, 0, 20));
