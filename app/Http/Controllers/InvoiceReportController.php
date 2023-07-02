<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use Illuminate\Http\Request;

class InvoiceReportController extends Controller
{
    public function index(){
        return view('reports.invoice_report');
    }
    public function search(Request $request){
        if($request->radio == '1'){
            if($request->type && $request->start_at == '' && $request->end_at == ''){
                $invoices = Invoices::select('*')->where('status','=',$request->type)->get();
                $type = $request->type;
                return view('reports.invoice_report',compact('type','invoices'));
            }else{
                $start_at = date($request->start_at);
                $end_at = date($request->end_at);
                $type = $request->type;
                $invoices = Invoices::whereBetween('invoice_date',[$start_at,$end_at])->where('status','=',$request->type)->get();
                return view('reports.invoice_report',compact('start_at','end_at','type','invoices'));
            }
        }else{
            $invoices = Invoices::select('*')->where('invoice_number','=',$request->invoice_number)->get();
            return view('reports.invoice_report',compact('invoices'));
        }
    }
}
