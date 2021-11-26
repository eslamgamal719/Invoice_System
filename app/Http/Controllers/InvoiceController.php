<?php

namespace App\Http\Controllers;

use App\Exports\InvoiceExport;
use App\Invoice;
use App\invoices_attachments;
use App\invoices_details;
use App\Notifications\AddInvoice;
use App\Notifications\AddInvoiceNotification;
use App\Product;
use App\Section;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::with('section')->get();
        return view('invoices.invoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sections = Section::all();
        return view('invoices.add_invoice', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $invoice_id = DB::table('invoices')->insertGetId([
            'invoice_number'    => $request->invoice_number,
            'invoice_date'      => $request->invoice_Date,
            'due_date'          => $request->Due_date,
            'product'           => $request->product,
            'section_id'        => $request->section,
            'amount_collection' => $request->Amount_collection,
            'amount_commission' => $request->Amount_Commission,
            'discount'          => $request->Discount,
            'value_vat'         => $request->Value_VAT,
            'rate_vat'          => $request->Rate_VAT,
            'total'             => $request->Total,
            'status'            => 'غير مدفوعة',
            'value_status'      => 2, 
            'note'              => $request->note,
        ]);

        invoices_details::create([
            'id_invoice' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'section' => $request->section,
            'status' => 'غير مدفوعة',
            'value_status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);

        if ($request->hasFile('pic')) {

            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            $attachments = new invoices_attachments();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->created_by = Auth::user()->name;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();

            // move pic
            $request->pic->move(public_path('attachments/' . $invoice_number), $file_name);

            session()->flash('add', 'تم اضافة الفاتوره بنجاح');
            return redirect('/invoices');
        }
        
        $auth_id = auth()->id();
        $users = User::where('id', '!=', $auth_id)->get();
        Notification::send($users, new AddInvoice($invoice_id));
       // $users->notify(new AddInvoice($invoice_id));  //send email

       // $users->notify(new AddInvoiceNotification($invoice_id)); //send notifocation
        Notification::send($users, new AddInvoiceNotification($invoice_id));

        session()->flash('add', 'تم اضافة الفاتوره بنجاح');
        return redirect('/invoices');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function status_show($id)
    {
         $invoice = Invoice::whereId($id)->first();
         return view('invoices.status_update', compact('invoice'));
    }


    public function status_update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        if($request->status === 'مدفوعة') {
            $invoice->update([
                'value_status' => 1,
                'status' => $request->status,
                'payment_date' => $request->payment_date
            ]);

            invoices_details::create([
                'id_invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->section,
                'status' =>  $request->status,
                'value_status' => 1,
                'note' => $request->note,
                'payment_date' => $request->payment_date,
                'user' => (Auth::user()->name),
            ]);
        }else {
            $invoice->update([
                'value_status' => 3,
                'status' => $request->status,
                'payment_date' => $request->payment_date
            ]);

            invoices_details::create([
                'id_invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->section,
                'status' =>  $request->status,
                'value_status' => 3,
                'note' => $request->note,
                'payment_date' => $request->payment_date,
                'user' => (Auth::user()->name),
            ]);
        }

        session()->flash('status_update');
        return redirect('/invoices');
    }


    public function invoice_paid()
    {
        $invoices = Invoice::where('value_status', 1)->get();
        return view('invoices.invoices_paid', compact('invoices'));
    }


    public function invoice_unpaid()
    {
        $invoices = Invoice::where('value_status', 2)->get();
        return view('invoices.invoices_unpaid', compact('invoices'));
    }


    public function invoice_partial()
    {
        $invoices = Invoice::where('value_status', 3)->get();
        return view('invoices.invoices_partial', compact('invoices'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sections = Section::all();
        $invoice = Invoice::findOrFail($id);
        return view('invoices.edit_invoice', compact('invoice', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $invoice = Invoice::find($request->invoice_id);
        $invoice->update([
            'invoice_number'    => $request->invoice_number,
            'invoice_date'      => $request->invoice_date,
            'due_date'          => $request->due_date,
            'product'           => $request->product,
            'section_id'        => $request->section,
            'amount_collection' => $request->amount_collection,
            'amount_commission' => $request->amount_commission,
            'discount'          => $request->discount,
            'value_vat'         => $request->value_vat,
            'rate_vat'          => $request->rate_vat,
            'total'             => $request->total,
            'note'              => $request->note,
        ]);

        session()->flash('edit', 'تم تعديل الفاتورة بنجاح');
        return redirect('/invoices');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $invoice_id = $request->invoice_id;
        $invoice = Invoice::whereId($invoice_id)->first();

        if($request->id_page == 2) {

            $invoice->delete();
            session()->flash('archive_invoice');
            return redirect('/invoices');

        }else {

            $attachment = invoices_attachments::where('invoice_id', $invoice_id)->first();

            if($attachment) {
                Storage::disk('public_uploads')->deleteDirectory($attachment->invoice_number);
            }
    
            $invoice->forceDelete();
            session()->flash('delete_invoice');
            return redirect('/invoices');

        }

    }


    public function getProducts($id) 
    {
        $products = Product::where('section_id', $id)->pluck('product_name', 'id');
        return json_encode($products);
    }


    public function print_invoice($id)
    {
        $invoice = Invoice::whereId($id)->first();
        return view('invoices.print_invoice', compact('invoice'));
    }


    public function export() 
    {
        return Excel::download(new InvoiceExport, 'قائمة الفواتير.xlsx');
    }






}
