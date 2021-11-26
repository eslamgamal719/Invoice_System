<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Section;
use Illuminate\Http\Request;

class CustomerReportController extends Controller
{
    
    public function index()
    {
        $sections = Section::all();
        return view('reports.customer_report', compact('sections'));
    }


    public function search_invoices(Request $request)
    {
        if ($request->section && $request->product && $request->start_at == '' && $request->end_at == '') { //في حالة البحث بدون التاريخ

            $invoices = Invoice::select('*')->where('section_id', $request->section)->where('product', $request->product)->get();
            $sections = Section::all();
            return view('reports.customer_report', compact('sections'))->withDetails($invoices);
      
        }else { // في حالة البحث بتاريخ
         
            $start_at = date($request->start_at);
            $end_at = date($request->end_at);
    
            $invoices = Invoice::whereBetween('invoice_date',[$start_at,$end_at])->where('section_id', $request->section)->where('product', $request->product)->get();
            $sections = Section::all();
            return view('reports.customer_report', compact('sections', 'start_at', 'end_at'))->withDetails($invoices);
       }
    }
}
