<?php

namespace App\Http\Controllers;

use App\Models\Invoice_attachment;
use App\Models\Invoice_details;
use App\Models\Invoices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use Illuminate\Support\Facades\DB;

class InvoiceDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice_details $invoice_details)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoices = Invoices::where('id',$id)->first();
        $invoice_details = Invoice_details::where('id_invoice',$id)->get();
        $invoice_attachments = Invoice_attachment::where('invoice_id',$id)->get();
        
        $getID = DB::table('notifications')->where('data->invoice_id',$id)->pluck('id');
        // dd($getID);
        DB::table('notifications')->where('id',$getID)->update(['read_at' => now()]);

        return view('invoices.invoice_details',compact('invoices','invoice_details','invoice_attachments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice_details $invoice_details)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        
        $invoices = Invoice_attachment::find($request->id_file);
        $invoices->delete();
        Storage::disk('public_upload')->delete($request->invoice_number.'/'.$request->file_name);
        session()->flash('delete', 'تم حذف المرفق بنجاح');
        return back();
    }
    public function openFile($invoice_number,$file_name)
    {
        $files = public_path('attachment/'.$invoice_number.'/'.$file_name);
        return response()->file($files);
    
    
    }
    public function get_file($invoice_number,$file_name)

    {
        $contents= public_path('attachment/'.$invoice_number.'/'.$file_name);
        return response()->download( $contents);
    }
}
