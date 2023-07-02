<?php

namespace App\Http\Controllers;

use App\Models\Invoice_attachment;
use App\Models\Invoice_details;
use App\Models\Invoices;
use App\Models\Product;
use App\Models\Section;
use App\Models\User;
use App\Notifications\InvoiceAdd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use App\Exports\InvoicesExport;
use App\Notifications\NewInvoiceNotification;
use Maatwebsite\Excel\Facades\Excel;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoices::all();
        return view('invoices.invoices',compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = Section::all();
        return view('invoices.create',compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Invoices::create([
            'invoice_number' => $request->invoice_number,
            'invoice_date'   => $request->invoice_date,
            'due_date'       => $request->due_date,
            'section_id'     => $request->section,
            'product'        => $request->product,
            'amount_collection' => $request->amount_collection,
            'amount_commission' => $request->amount_commission,
            'discount'        => $request->discount,
            'rate_vat'        => $request->rate_vat,
            'value_vat'       => $request->value_vat,
            'total'        => $request->total,
            'status'       => 'غير مدفوعة',
            'value_status' => 2,
            'note'         => $request->note,
            'payment_date' => $request->payment_date,
        ]);
        $id_invoice = Invoices::latest()->first()->id;
        Invoice_details::create([
            'id_invoice' => $id_invoice,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'section'  => $request->section,
            'status' => 'غير مدفوعة',
            'value_status' => 2,
            'note' => $request->note,
            'user' => auth()->user()->name,
        ]);
        if($request->hasFile('pic')){
            
            $id_invoice = Invoices::latest()->first()->id;
            $image = $request->file('pic');
            $fileName = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            $attachment = new Invoice_attachment();
            $attachment->file_name = $fileName;
            $attachment->invoice_number = $invoice_number;
            $attachment->Created_by = auth()->user()->name;
            $attachment->invoice_id = $id_invoice;
            $attachment->save();
            
            $imageName = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('attachment/'.$invoice_number),$imageName);
        }
        //send mail
        // $user = User::first();
        // Notification::send($user, new InvoiceAdd($id_invoice));

        //send notification
        // $user = User::get(); // ارسال اشعارات للكل
        // $user = User::find(Auth::user()->id); //  ارسال اشعار فقط للشخص الذي قام بالاضافة
        $user = User::find(auth()->user()->id);
        $user = User::first();
        $invoice = Invoices::latest()->first();
        Notification::send($user , new NewInvoiceNotification($invoice));
        
        session()->flash('Add','تم اضافة الفاتورة بنجاح');
        return back();

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invoices = Invoices::findOrFail($id);
        return view('invoices.status_update',compact('invoices'));
    }

    public function status_update(Request $request , $id)
    {
        // dd($request);
        $invoices = Invoices::findOrFail($id);
        if($request->status === 'مدفوعة'){
            $invoices->update([
                'value_status' => 1,
                'payment_date' => $request->payment_date,
                'status' => $request->status
            ]);
            Invoice_details::create([
                'id_invoice' => $id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->section,
                'status' => $request->status,
                'value_status' => 1,
                'payment_date' => $request->payment_date,
                'note' => $request->note,
                'user'=> auth()->user()->name,
            ]);
        }else{
            $invoices->update([
                'value_status' => 3,
                'payment_date' => $request->payment_date,
                'status' => $request->status
            ]);
            Invoice_details::create([
                'id_invoice' => $id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->section,
                'status' => $request->status,
                'value_status' => 3,
                'payment_date' => $request->payment_date,
                'note' => $request->note,
                'user'=> auth()->user()->name,
            ]);
            session()->flash('update_status');
            return redirect('/invoices');
        }

    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoices = Invoices::where('id',$id)->first();
        $sections = Section::all();
        return view('invoices.edit',compact('invoices','sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $invoice = Invoices::findOrFail($request->id);
        $invoice->update([
            'invoice_number' => $request->invoice_number,
            'invoice_date'   => $request->invoice_date,
            'due_date'       => $request->due_date,
            'section_id'     => $request->section,
            'product'        => $request->product,
            'amount_collection' => $request->amount_collection,
            'amount_commission' => $request->amount_commission,
            'discount'        => $request->discount,
            'rate_vat'        => $request->rate_vat,
            'value_vat'       => $request->value_vat,
            'total'        => $request->total,
            'note'         => $request->note,
        ]);
        session()->flash('edit', 'تم تعديل الفاتورة بنجاح');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // dd($request);
        $id = $request->invoice_id;
        $invoice = Invoices::where('id',$id)->first();
        $id_page = $request->id_page;
        // $invoice->delete(); //soft delete
        //force delete and delete attachment
        $attachment = Invoice_attachment::where('invoice_id',$id)->first();
        
        if($id_page == 2){
            $invoice->delete();
            session()->flash('archive_invoice');
            return redirect('/invoices');
            
        }else{
            if(!empty( $attachment->invoice_number)){
                Storage::disk('public_upload')->deleteDirectory($attachment->invoice_number);
            }
            $invoice->forceDelete();
            session()->flash('delete_invoice');
            return redirect('/invoices');
        }
    }
    public function getproducts($id){
        $products = DB::table('products')->where('section_id',$id)->pluck('product_name','id');
        return json_encode($products);
    }
    public function invoice_paid(){
        $invoices = Invoices::where('value_status',1)->get();
        return view('invoices.invoice_paid',compact('invoices'));
    }
    public function invoice_unpaid(){
        $invoices = Invoices::where('value_status',2)->get();
        return view('invoices.invoice_unpaid',compact('invoices'));
    }
    public function invoice_part(){
        $invoices = Invoices::where('value_status',3)->get();
        return view('invoices.invoice_part',compact('invoices'));
    }
    public function print_invoice($id){
        
        $invoice = Invoices::where('id',$id)->first();
        return view('invoices.print_invoice',compact('invoice'));
    }
    public function export() 
    {
        return Excel::download(new InvoicesExport, 'invoices.xlsx');
    }
    public function MarkAsRead(){
        $userunreadnotification = auth()->user()->unreadNotifications;
        if($userunreadnotification){
            $userunreadnotification->markAsRead();
            return back();
        }
    }
}
