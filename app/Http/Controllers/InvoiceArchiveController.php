<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\invoices_attachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceArchiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::onlyTrashed()->get();
        return view('invoices.archive_invoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $invoice_id = $request->invoice_id;
        $invoice = Invoice::withTrashed()->whereId($invoice_id)->first();

        $attachment = invoices_attachments::where('invoice_id', $invoice_id)->first();

        if($attachment) {
            Storage::disk('public_uploads')->deleteDirectory($attachment->invoice_number);
        }

        $invoice->forceDelete();
        session()->flash('delete_invoice');
        return redirect('/invoices_archives');

      
    }


    public function restore_invoice(Request $request)
    {
        Invoice::withTrashed()->whereId($request->invoice_id)->restore();
        session()->flash('restore_invoice');
        return redirect('/invoices_archives');
    }
}
