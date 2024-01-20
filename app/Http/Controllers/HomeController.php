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
        $data_chart1 = Chart1::whereBetween('datetime', [$start_datetime, $end_datetime])->get();
        $data_chart2 = Chart2::whereBetween('datetime', [$start_datetime, $end_datetime])->get();
        $data_chart3 = Chart3::whereBetween('datetime', [$start_datetime, $end_datetime])->get();
        $display_data = ['data_chart1' => $data_chart1, 'data_chart2' => $data_chart2, 'data_chart3' => $data_chart3];
        echo json_encode($display_data);
    }

    public function display_data_live()
    {
        $tags = [];

        $limit_size = 10;
        $data_chart1 = Chart1::orderBy('id', 'DESC')->limit($limit_size)->get();
        $data_chart2 = Chart2::orderBy('id', 'DESC')->limit($limit_size)->get();
        $data_chart3 = Chart3::orderBy('id', 'DESC')->limit($limit_size)->get();

        for ($i = 0; $i < $limit_size; $i++) {
            $str = $data_chart1[$i]['datetime'];
            $time = substr($str, 11, 5);
            array_push($tags, $time);
        }

        $drillingChartData = $this->getDrillingChartData($data_chart1);
        $pressureChartData = $this->getPressureChartData($data_chart2);
        $mudChartData = $this->getMudChartData($data_chart3);

        $display_data = ['drillingParameters' => $drillingChartData, 'pressureParameters' => $pressureChartData, 'mudParameters' => $mudChartData, 'tags' => $tags];
        echo json_encode($display_data);
    }

    private function getDrillingChartData($data)
    {
        $SURFRPM = array_map(function ($object) {
            return $object->SURFRPM;
        }, $data);
        $WOB = array_map(function ($object) {
            return $object->WOB;
        }, $data);
        $BITRPM = array_map(function ($object) {
            return $object->BITRPM;
        }, $data);
        $TORQ = array_map(function ($object) {
            return $object->TORQ;
        }, $data);
        $BLKPOSCOMP = array_map(function ($object) {
            return $object->BLKPOSCOMP;
        }, $data);
        $HKLD = array_map(function ($object) {
            return $object->HKLD;
        }, $data);
        return array("SURFRPM" => $SURFRPM, "WOB" => $WOB, "BITRPM" => $BITRPM, "TORQ" => $TORQ, "BLKPOSCOMP" => $BLKPOSCOMP, "HKLD" => $HKLD);
    }

    private function getPressureChartData($data)
    {
        $SPP = array_map(function ($object) {
            return $object->SPP;
        }, $data);
        $CSGP = array_map(function ($object) {
            return $object->CSGP;
        }, $data);
        $SPM01 = array_map(function ($object) {
            return $object->SPM01;
        }, $data);
        $SPM02 = array_map(function ($object) {
            return $object->SPM02;
        }, $data);
        $SPM03 = array_map(function ($object) {
            return $object->SPM03;
        }, $data);
        $FLOWIN = array_map(function ($object) {
            return $object->FLOWIN;
        }, $data);
        return array("SPP" => $SPP, "CSGP" => $CSGP, "SPM01" => $SPM01, "SPM02" => $SPM02, "SPM03" => $SPM03, "FLOWIN" => $FLOWIN);
    }

    private function getMudChartData($data)
    {
        $PITACTIVE = array_map(function ($object) {
            return $object->PITACTIVE;
        }, $data);
        $FLOWOUTP = array_map(function ($object) {
            return $object->FLOWOUTP;
        }, $data);
        $TGAS = array_map(function ($object) {
            return $object->TGAS;
        }, $data);
        return array("PITACTIVE" => $PITACTIVE, "FLOWOUTP" => $FLOWOUTP, "TGAS" => $TGAS);
    }
}
