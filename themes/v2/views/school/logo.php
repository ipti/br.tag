<?php

    header('Content-Type: '.$model->logo_file_type);
    print $model->logo_file_content;
    exit();

?>