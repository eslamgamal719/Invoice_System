<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Product;
use App\Section;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:المنتجات', ['only' => ['index']]);
        $this->middleware('permission:اضافة منتج', ['only' => ['store']]);
        $this->middleware('permission:تعديل منتج', ['only' => ['update']]);
        $this->middleware('permission:حذف منتج', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sections = Section::all();
        $products = Product::with('section')->get();
        return view('products.products', compact('sections', 'products'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\ProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {    
        Product::create([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'section_id' => $request->section_id,
        ]);

        session()->flash('add', 'تم اضافة المنتج بنجاح');
        return redirect('/products');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validation = $request->validate([
            'product_name' => 'required|max:255',
            'description' => 'nullable',
            'section_name' => 'required',
        ], [
            'product_name.required' => 'الرجاء ادخال اسم المنتج',
            'section_name.required' => 'الرجاء اختيار القسم',
        ]);

        $section_id = Section::where('section_name', $request->section_name)->first()->id;
        $product = Product::FindOrFail($request->pro_id);
        $product->update([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'section_id' => $section_id,
        ]);

        session()->flash('edit', 'تم تعديل المنتج بنجاح');
        return redirect('/products');
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $product = Product::findOrFail($request->pro_id);
        $product->delete();
        
        session()->flash('delete', 'تم حذف المنتج بنجاح');
        return redirect('/products');
    }
}
