<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::all();
        $products = Product::all();
        return view('products.products',compact('sections','products'));
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
        $request->validate([
            'product_name' => 'required|max:255',
        ],[
            'product_name.required' => 'يرجى ادخال اسم المنتج',
            // 'product_name.unique' =>'اسم المنتج مسجل مسبقا',
        ]);
        Product::create([
            'product_name' => $request->product_name,
            'section_id'   => $request->section_id,
            'description'  => $request->description
        ]);
        session()->flash('Add','تم اضافة المنتج بنجاح');
        return redirect('/products');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->id;
        $section_id = Section::where('section_name',$request->section_name)->first()->id;
        $this->validate($request,[
            'product_name' => 'required|max:255|unique:products,product_name,'.$id,
        ],[
            'product_name.required' => 'يرجى ادخال اسم المنتج',
            'product_name.unique' =>'اسم المنتج مسجل مسبقا',
        ]);
        
        $products = Product::find($id);
        $products->update([
            'product_name' => $request->product_name,
            'section_id'   => $section_id,
            'description'  => $request->description
        ]);
            
        
        session()->flash('edit','تم تعديل المنتج بنجاح');
        return redirect('/products');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $products = Product::find($request->id);
        $products->delete();
        session()->flash('delete','تم حذف المنتج بنجاح');
        return redirect('/products');
    }
}
