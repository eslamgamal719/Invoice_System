<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectionRequest;
use App\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:الاقسام', ['only' => ['index']]);
        $this->middleware('permission:اضافة قسم', ['only' => ['update']]);
        $this->middleware('permission:حذف قسم', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sections = Section::all();
        return view('sections.sections', compact('sections'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SectionRequest $request)
    {
        Section::create([
            'section_name' => $request->section_name,
            'description' => $request->description,
            'created_by' => (Auth::user()->name),
        ]);

        session()->flash('add', 'تم اضافة القسم بنجاح ');
        return redirect('/sections');
    }
    


    public function update(SectionRequest $request)
    {
        $section = Section::findOrFail($request->id);
        $section->update([
            'section_name' => $request->section_name,
            'description'  => $request->description,
        ]);

        session()->flash('edit', 'تم تعديل القسم بنجاح');
        return redirect('/sections');
    }



    public function destroy(Request $request)
    {
        $section = Section::findOrFail($request->id);
        $section->delete();

        session()->flash('delete', 'تم حذف القسم بنجاح');
        return redirect('/sections');
    }
}
