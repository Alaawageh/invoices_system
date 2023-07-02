<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::all();
        return view('sections.sections',compact('sections'));
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
            'section_name' => 'required|unique:sections|max:255',
        ],[
            'section_name.required' =>'يرجي ادخال اسم القسم',
            'section_name.unique' =>'اسم القسم مسجل مسبقا',
        ]);
        Section::create([
            'section_name' => $request->section_name,
            'description'  => $request->description,
            'Created_by'   => auth()->user()->name,
        ]);
        session()->flash('Add', 'تم اضافة القسم بنجاح ');
        return redirect('/sections');
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->id;
        $this->validate($request,[
            'section_name' => 'required|max:255|unique:sections,section_name,'.$id,
        ],[
            'section_name.required' =>'يرجي ادخال اسم القسم',
            'section_name.unique' =>'اسم القسم مسجل مسبقا',
        ]);
        $sections = Section::find($id);
        $sections->update([
            'section_name' => $request->section_name,
            'description'  => $request->description,
        ]);
        session()->flash('edit','تم تعديل البيانات بنجاح');
        return redirect('/sections');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        Section::find($id)->delete();
        session()->flash('delete','تم حذف البيانات بنجاح');
        return redirect('/sections');

    }
}
