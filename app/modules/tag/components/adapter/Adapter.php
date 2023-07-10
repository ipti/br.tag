<?php

class Adapter implements ExportableInterface, ImportableInterface
{
    function export($data) {     
        return json_encode($data);
    }

    function import($data) {
        return json_decode($data, true);  
    }
}
