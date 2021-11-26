<?php

namespace App\Http\Controllers;

use App\Invoice;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $count_all = Invoice::count();
        $count_unpaid = Invoice::where('value_status', 2)->count();
        $percent_unpaid = ($count_unpaid/$count_all) * 100;

        $count_paid = Invoice::where('value_status', 1)->count();
        $percent_paid = ($count_paid/$count_all) * 100;

        $count_partial_paid = Invoice::where('value_status', 3)->count();
        $percent_partial_paid = ($count_partial_paid/$count_all) * 100;

        $count_unpaid == 0 ? $percent_unpaid = 0 : $percent_unpaid = ($count_unpaid/$count_all) * 100;
        $count_paid == 0 ? $percent_paid = 0 : $percent_paid = ($count_paid/$count_all) * 100;
        $count_partial_paid == 0 ? $percent_partial_paid = 0 : $percent_partial_paid = ($count_partial_paid/$count_all) * 100;

        $chartjs = app()->chartjs
         ->name('barChartTest')
         ->type('bar')
         ->size(['width' => 300, 'height' => 200])
         ->labels(['الفواتير الغير مدفوعة', 'الفواتير المدفوعة', 'الفواتير المدفوعة جزئيا'])
         ->datasets([
           /*  [
                 "label" => "نسبة الفواتير",
                 'backgroundColor' => ['#D83A56', '#064420', '#FF7600'],   //الفواتيرالغيرالمدفوعة + الفواتير المدفوعه جزئيا
                 'data' => [$percent_unpaid, $percent_paid, $percent_partial_paid]  //نسبة الفواتير الغير مدفوعة + نسبة الفواتير المدفوعة جزئيا
             ],*/
             [
                "label" => "الفواتير الغير المدفوعة",
                'backgroundColor' => ['#ec5858'],
                'data' => [$percent_unpaid]
            ],
            [
                "label" => "الفواتير المدفوعة",
                'backgroundColor' => ['#81b214'],
                'data' => [$percent_paid]
            ],
            [
                "label" => "الفواتير المدفوعة جزئيا",
                'backgroundColor' => ['#ff9642'],
                'data' => [$percent_partial_paid]
            ],
         ])
         ->options([]);



         $chartjs_2 = app()->chartjs
         ->name('pieChartTest')
         ->type('pie')
         ->size(['width' => 340, 'height' => 200])
         ->labels(['الفواتير الغير المدفوعة', 'الفواتير المدفوعة','الفواتير المدفوعة جزئيا'])
         ->datasets([
             [
                 'backgroundColor' => ['#ec5858', '#81b214','#ff9642'],
                 'data' => [$percent_unpaid, $percent_paid, $percent_partial_paid]
             ]
         ])
         ->options([]);

         return view('home', compact('chartjs','chartjs_2'));
    }

}
