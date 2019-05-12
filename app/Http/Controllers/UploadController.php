<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\Invoice;
use App\Account;
use App\Http\Requests\UploadOfxRequest;
use App\Http\Requests\UploadCsvRequest;
use App\Helpers\Upload\OFX;
use App\Helpers\Upload\CSV;
class UploadController extends ApplicationController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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
        foreach ($request->file('ofx-file') as $file) {
            $ofx = new OFX($file);
            if ($request->account->is_credit_card && !$request->invoice_id){
                $description = $file->getClientOriginalName();
                $dateInit = date("Y-m-d\TH:i:s", strtotime($ofx->getDateInit()));
                $dateEnd = date("Y-m-d\TH:i:s", strtotime($ofx->getDateEnd()));
                $request->invoice_id = $this->invoice($request->account->id, $description, $dateInit, $dateEnd)->id;
            }
            for ($index = 0; $index < $ofx->size(); $index++)
                $this->transaction($request->account->id, $request->invoice_id, $ofx->getTransaction($index));
        }
        return redirect(route('accounts.index'));
    }

    /**
     * @param UploadCsvRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function csv(UploadCsvRequest $request)
    {
        foreach ($request->file('csv-file') as $file) {
            $csv = new CSV($file);
            if ($request->account->is_credit_card && !$request->invoice_id){
                $description = $file->getClientOriginalName();
                $dateInit = date("Y-m-d\TH:i:s", strtotime($csv->getFirst("date")));
                $dateEnd = date("Y-m-d\TH:i:s", strtotime($csv->getLast("date")));
                $request->invoice_id = $this->invoice($request->account->id, $description, $dateInit, $dateEnd)->id;
            }
            for ($line = 0; $line < $csv->size(); $line++)
                $this->transaction($request->account->id, $request->invoice_id, $csv->getLine($line));
        }
        return redirect(route('accounts.index'));
    }

    private function invoice($accountId, $description, $dateInit, $dateEnd){
        return Invoice::create([
            'date_end' => $dateEnd,
            'date_init' => $dateInit,
            'account_id' => $accountId,
            'description' => "Invoice " . $description,
            'debit_date' => date("Y-m-d", strtotime("+5 day", strtotime($dateEnd)))
        ]);
    }

    private function transaction($accountId, $invoiceId, $data){
        return Transaction::create([
            'paid' => true,
            'account_id' => $accountId,
            'invoice_id' => $invoiceId,
            'value' => $data["value"] * 1,
            'description' => $data["description"],
            'date' => date("Y-m-d\TH:i:s", strtotime($data["date"]))
        ]);
    }
}
