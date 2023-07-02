<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use App\Models\Section;
use Illuminate\Http\Request;

class CustomReportController extends Controller
{
    public function index(){
        $sections = Section::all();
        return view('reports.custom_report',compact('sections'));
    }
    public function search_report(Request $request){
        if($request->section && $request->product && $request->start_at =='' && $request->end_at==''){
            $invoices = Invoices::where('section_id','=',$request->section)->where('product','=',$request->product)->get();
            $sections = Section::all();
            return view('reports.custom_report',compact('sections','invoices'));
        }else{
            $start_at = date($request->start_at);
            $end_at = date($request->end_at);
            $sections = Section::all();
            $invoices = invoices::whereBetween('invoice_date',[$start_at,$end_at])->where('section_id','=',$request->section)->where('product','=',$request->product)->get();
            return view('reports.custom_report',compact('start_at','end_at','sections','invoices'));
        }
    }
}
