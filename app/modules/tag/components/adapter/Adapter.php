<?php

class Adapter implements ExportableInterface, ImportableInterface
{
    /**
     * Summary of export
     * @param mixed $data
     * @return bool|string
     */
    public function export($data) {     
        return json_encode($data);
    }

    /**
     * Summary of import
     * @param mixed $data
     * @return mixed
     */
    public function import($data) {
        return json_decode($data, true);  
    }
}
