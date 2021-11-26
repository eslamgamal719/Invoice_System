<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Section;
use Illuminate\Http\Request;

class InvoicesReportController extends Controller
{
    
    public function index()
    {
        return view('reports.invoices_reports');
    }



    public function search_invoices(Request $request)
    {
        //return $request;
        $radio = $request->radio;
        if ($radio == 1) {  // في حالة البحث بنوع الفاتورة
        
            if ($request->type && $request->start_at == '' && $request->end_at == '') {  // في حالة عدم تحديد تاريخ
                
                $invoices = Invoice::select('*')->where('status', $request->type)->get();
                $type = $request->type;

                return view('reports.invoices_reports',compact('type'))->withDetails($invoices);

            }else { // في حالة تحديد تاريخ استحقاق
           
                $start_at = date($request->start_at);
                $end_at = date($request->end_at);
                $type = $request->type;
          
                $invoices = Invoice::whereBetween('invoice_date',[$start_at, $end_at])
                            ->where('status', $request->type)
                            ->get();

                return view('reports.invoices_reports',compact('type','start_at','end_at'))->withDetails($invoices);
            }

        }else {  // في البحث برقم الفاتورة
            
            $invoices = Invoice::select('*')
            ->where('invoice_number',  $request->invoice_number)
            ->get();

            return view('reports.invoices_reports')->withDetails($invoices);
        }
    }
    




}
