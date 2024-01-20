<?php

namespace App\Http\Controllers;

use App\Models\Chart1;
use App\Models\Chart2;
use App\Models\Chart3;
use App\Models\ChartData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return view('home');
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }


    public function display_data_history($start_datetime, $end_datetime)
    {
        $start_datetime = str_replace('*', ' ', $start_datetime);
        $end_datetime = str_replace('*', ' ', $end_datetime);
//    echo json_encode([$start_datetime,$end_datetime]);
        $data_chart1 = Chart1::where([
            'datetime', '>=', $start_datetime,
            'datetime', '<=', $end_datetime
        ])->get();
        echo json_encode($data_chart1);
//    $data_chart2=Chart2::where('datetime','>=',$start_datetime)->where('datetime','<=',$$end_datetime)->get();
//    $data_chart3=Chart3::where('datetime','>=',$start_datetime)->where('datetime','<=',$$end_datetime)->get();
//    $display_data=['data_chart1'=>$data_chart1,'data_chart2'=>$data_chart2,'data_chart3'=>$data_chart3];
//    echo json_encode($display_data) ;
    }

    public function display_data_live()
    {
        $data_chart1 = Chart1::orderBy('id', 'DESC')->limit(10)->get();
        $data_chart2 = Chart2::orderBy('id', 'DESC')->limit(10)->get();
        $data_chart3 = Chart3::orderBy('id', 'DESC')->limit(10)->get();

        $labels = ['2'];

        $display_data = ['drillingParameters' => $data_chart1, 'pressureParameters' => $data_chart2, 'mudParameters' => $data_chart3, 'labels' => $labels];
        echo json_encode($display_data);
    }
}
