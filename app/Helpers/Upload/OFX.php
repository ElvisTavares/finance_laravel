<?php

namespace App\Helpers\Upload;

class OFX
{
    private $filename;
    private $fixed = "";
    private $bankAccountInfo;

    public function __construct($filename, $delimiter = ','){
        if (!file_exists($filename) || !is_readable($filename)) throw new \Exception('File not found.');
        $this->filename = $filename;
        $this->load();
    }

    private function openFile(){
        $handle = fopen($this->filename, 'r');
        if ($handle === false) throw new Exception('File not parsed.');
        return $handle;
    }

    private function load()
    {
        $handle = $this->openFile();
        while (($line = fgets($handle)) !== false) {
            $line = trim($line);
            if (starts_with($line, "<TRNAMT>") && ends_with($line, "00")){
                $line.="00";
            }
            $this->fixed .= $line."\n";
        }
        $parser = new \OfxParser\Parser();
        $ofx = $parser->loadFromString($this->fixed);
        $bankAccount = reset($ofx->bankAccounts);
        $this->bankAccountInfo = $bankAccount->statement;
        fclose($handle);
    }

    public function getDateInit(){
        return $this->bankAccountInfo->startDate;
    }

    public function getDateEnd(){
        return $this->bankAccountInfo->endDate;
    }

    public function getTransaction($index){
        $transaction = $this->bankAccountInfo->transactions[$index];
        return [
            "date" => date("Y-m-d\TH:i:s", $transaction->date->getTimestamp()),
            "value" => $transaction->amount,
            "description" => $transaction->memo
        ];
    }

}