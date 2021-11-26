<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\invoices_details;
use App\invoices_attachments;

class NotificationsController extends Controller
{
    
    public function markAsReadAll()
    {
        $userUnreadNotifications = auth()->user()->unreadNotifications;

        if($userUnreadNotifications) {
            $userUnreadNotifications->markAsRead();
            return redirect()->back();
        }
    }



    public function invoices_details($id, $notify_id)
    {
        $userUnreadNotifications = auth()->user()->unreadNotifications;
        foreach($userUnreadNotifications as $notification) {
            $invoice_notification = $userUnreadNotifications->where('id', $notify_id)->first();
        }
        
        if(isset($invoice_notification)) {
            $invoice_notification->markAsRead();
        }

        $invoice = Invoice::whereId($id)->first();
        $details = invoices_details::where('id_invoice', $id)->get();
        $attachments = invoices_attachments::where('invoice_id', $id)->get();
        return view('invoices.details_invoices', compact('invoice', 'details', 'attachments'));
    }
}
