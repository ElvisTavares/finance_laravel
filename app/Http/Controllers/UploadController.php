<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\Invoice;
use App\Account;
use App\Http\Requests\UploadOfxRequest;
use App\Http\Requests\UploadCsvRequest;

class UploadController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'account']);
    }

    private function adjustValuesOfx(&$item){
        $keyValue = get_object_vars($item);
        foreach ($keyValue as $key => $value){
            if ($key == 'TRNAMT' && !contains($value, '.')){
                $item->$key = preg_replace( "/\r|\n/", "", $value.'.00' );;
            } else{
                if (is_object($value)) {
                    $this->adjustValuesOfx($value);
                } elseif (is_array($value)){
                    foreach ($value as $v){
                        $this->adjustValuesOfx($v);
                    }
                }
            }
        }
        return $item;
    }

    /**
     * Process an oxf file
     *
     * @param UploadOfxRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function ofx(UploadOfxRequest $request)
    {
        $account = Account::find($request->account);
        if (isset($invoice)) {
            $this->middleware('invoice');
            $invoice = Invoice::find($request->invoice);
        }
        foreach ($request->file('ofx-file') as $file) {
            $xml = $this->oxfToXml($file);
            $ofx = new \OfxParser\Ofx($xml);
            $bankAccount = reset($ofx->bankAccounts);
            $bankAccountInfo = $bankAccount->statement;
            $startDate = $bankAccountInfo->startDate;
            $endDate = $bankAccountInfo->endDate;
            if (!isset($request->invoice) && $account->is_credit_card) {
                $invoice = new Invoice;
                $invoice->account()->associate($account);
                $invoice->description = "Invoice " . $file->getClientOriginalName();
                $invoice->date_init = date("Y-m-d\TH:i:s", $startDate->getTimestamp());
                $invoice->date_end = date("Y-m-d\TH:i:s", $endDate->getTimestamp());
                $invoice->debit_date = new \DateTime();
                $invoice->save();
            }
            foreach ($bankAccountInfo->transactions as $ofxTransaction) {
                $transaction = new Transaction;
                $transaction->account()->associate($account);
                $transaction->date = date("Y-m-d\TH:i:s", $ofxTransaction->date->getTimestamp());
                $transaction->description = $ofxTransaction->memo;
                $transaction->value = $ofxTransaction->amount;
                $transaction->paid = true;
                if (isset($invoice) && $account->is_credit_card) {
                    $transaction->invoice()->associate($invoice);
                }
                $transaction->save();
            }
        }
        return redirect('/accounts/');
    }

    /**
     * @param UploadCsvRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function csv(UploadCsvRequest $request)
    {
        $account = Account::find($request->account);
        if (isset($invoice)) {
            $this->middleware('invoice');
            $invoice = Invoice::find($request->invoice);
        }
        foreach ($request->file('csv-file') as $file) {
            $csvData = $this->csvToArray($file);
            if (!isset($request->invoice) && $account->is_credit_card) {
                $invoice = new Invoice;
                $invoice->account()->associate($account);
                $invoice->description = "Invoice " . $file->getClientOriginalName();
                $invoice->date_init = date("Y-m-d\TH:i:s", strtotime($csvData[0]["date"]));
                $invoice->date_end = date("Y-m-d\TH:i:s", strtotime($csvData[count($csvData) - 1]["date"]));
                $invoice->debit_date = new \DateTime();
                $invoice->save();
            }
            foreach ($csvData as $csvTransaction) {
                $transaction = new Transaction;
                $transaction->account()->associate($account);
                $transaction->date = date("Y-m-d\TH:i:s", strtotime($csvTransaction["date"]));
                $transaction->description = $csvTransaction["description"];
                $transaction->value = $csvTransaction["value"] * 1;
                $transaction->paid = true;
                if (isset($invoice) && $account->is_credit_card) {
                    $transaction->invoice()->associate($invoice);
                }
                $transaction->save();
            }
        }
        return redirect('/accounts/');
    }

    /**
     * Function to pre process function because simple error on https://github.com/asgrim/ofxparser not fixed
     * @param $file
     * @return bool|mixed|string
     */
    public function oxfToXml($file)
    {
        $content = file_get_contents($file);
        $line = strpos($content, "<OFX>");
        $ofx = substr($content, $line - 1);
        $buffer = $ofx;
        $count = 0;
        while ($pos = strpos($buffer, '<')) {
            $count++;
            $pos2 = strpos($buffer, '>');
            $element = substr($buffer, $pos + 1, $pos2 - $pos - 1);
            if (substr($element, 0, 1) == '/') {
                $sla[] = substr($element, 1);
            } else {
                $als[] = $element;
            }
            $buffer = substr($buffer, $pos2 + 1);
        }
        $adif = array_diff($als, $sla);
        $adif = array_unique($adif);
        $ofxy = $ofx;
        foreach ($adif as $dif) {
            $dpos = 0;
            while ($dpos = strpos($ofxy, $dif, $dpos + 1)) {
                $npos = strpos($ofxy, '<', $dpos + 1);
                $ofxy = substr_replace($ofxy, "</$dif>\n<", $npos, 1);
                $dpos = $npos + strlen($element) + 3;
            }
        }
        $ofxy = str_replace('&', '&amp;', $ofxy);
        return simplexml_load_string(utf8_encode($ofxy));
    }


    /**
     * Transform csv into array, code in https://github.com/danielino/Utils/blob/master/csvToArray.php
     * @param string $filename
     * @param string $delimiter
     * @return array|bool
     */
    private function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }
        $header = null;
        $data = [];
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }
        return $data;
    }
}
