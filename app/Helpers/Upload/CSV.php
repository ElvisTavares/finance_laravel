<?php

namespace App\Helpers\Upload;

class CSV
{
    private $filename;
    private $delimiter = ',';
    private $data = [];

    public function __construct($filename, $delimiter = ','){
        if (!file_exists($filename) || !is_readable($filename)) throw new Exception('File not found.');
        $this->filename = $filename;
        $this->delimiter = $delimiter;
        $this->load();
    }

    public function size(){
        return count($this->data);
    }

    public function getLine($line){
        return $this->data[$line];
    }

    public function getValue($line, $column){
        return $this->data[$line][$column];
    }

    public function getFirst($column){
        return $this->data[0][$column];
    }

    public function getLast($column){
        return $this->data[$this->size() - 1][$column];
    }

    private function openFile(){
        $handle = fopen($this->filename, 'r');
        if ($handle === false) throw new Exception('File not parsed.');
        return $handle;
    }
    /**
     * Transform csv into array, code in https://github.com/danielino/Utils/blob/master/csvToArray.php
     * @return array|bool
     */
    private function load()
    {
        $handle = $this->openFile();
        while (($row = fgetcsv($handle, 1000, $this->delimiter)) !== false) {
            if (!$header){
                $header = $row;
                continue;
            }
            $this->data[] = array_combine($header, $row);
        }
        fclose($handle);
    }
}