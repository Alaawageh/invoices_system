<?php

namespace App\Http\Controllers;

use App\Models\Invoice_attachment;
use Illuminate\Http\Request;

class InvoiceAttachmentController extends Controller
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
        $this->validate($request,[
            'file_name' => 'mimes:pdf,jpg,png,jpeg'
        ],[
            'file_name.mimes' => 'يجب ان تكون صيغة الملف pdf , png , jpg'
        ]
        
    );
        $image = $request->file('file_name');
        $fileName = $image->getClientOriginalName();
        $invoice_number = $request->invoice_number;

        $attachment = new Invoice_attachment();
        $attachment->file_name = $fileName;
        $attachment->invoice_number = $request->invoice_number;
        $attachment->Created_by = auth()->user()->name;
        $attachment->invoice_id = $request->invoice_id;
        $attachment->save();

        $imageName = $request->file_name->getClientOriginalName();
        $request->file_name->move(public_path('attachment/'.$invoice_number),$imageName);

        session()->flash('Add','تم اضافة المرفق بنجاح');
        return back();

    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice_attachment $invoice_attachment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice_attachment $invoice_attachment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice_attachment $invoice_attachment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice_attachment $invoice_attachment)
    {
        //
    }
}
