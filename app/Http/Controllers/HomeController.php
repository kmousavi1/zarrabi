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
        $display_data = [];
        $start_datetime = str_replace('*', ' ', $start_datetime);
        $end_datetime = str_replace('*', ' ', $end_datetime);

//        $start_datetime='2024-01-21 15:41:08';
//        $end_datetime='2024-01-21 15:41:10';

        $chartData = ChartData::whereBetween('datetime', [$start_datetime, $end_datetime])->get();


        if (count($chartData) > 0) {
            $chartData = $chartData->toArray();

            $tags = $this->getLabels($chartData);

            $display_data = $this->getDisplayData($chartData, $tags);
            echo json_encode($display_data);
        } else {
            echo json_encode($display_data);
        }
    }

    public function display_data_live()
    {

        $display_data = [];
        $limit_size = 10;

        $chartData = ChartData::orderBy('id', 'DESC')->limit($limit_size)->get();

        if (count($chartData) > 0) {
            $chartData = $chartData->toArray();

            $tags = $this->getLabels($chartData);

            $display_data = $this->getDisplayData($chartData, $tags);
            echo json_encode($display_data);
        } else {
            echo json_encode($display_data);
        }
    }

    private function getDrillingChartData($data)
    {
        $SURFRPM = array_map(function ($object) {
            return round($object['SURFRPM'], 5);
        }, $data);
        $WOB = array_map(function ($object) {
            return round($object['WOB'], 5);
        }, $data);
        $BITRPM = array_map(function ($object) {
            return round($object['BITRPM'], 5);
        }, $data);
        $TORQ = array_map(function ($object) {
            return round($object['TORQ'], 5);
        }, $data);
        $BLKPOSCOMP = array_map(function ($object) {
            return round($object['BLKPOSCOMP'], 5);
        }, $data);
        $HKLD = array_map(function ($object) {
            return round($object['HKLD'], 5);
        }, $data);
        return array("SURFRPM" => $SURFRPM, "WOB" => $WOB, "BITRPM" => $BITRPM, "TORQ" => $TORQ, "BLKPOSCOMP" => $BLKPOSCOMP, "HKLD" => $HKLD);
    }

    private function getPressureChartData($data)
    {
        $SPP = array_map(function ($object) {
            return round($object['SPP'], 5);
        }, $data);
        $CSGP = array_map(function ($object) {
            return round($object['CSGP'], 5);
        }, $data);
        $SPM01 = array_map(function ($object) {
            return round($object['SPM01'], 5);
        }, $data);
        $SPM02 = array_map(function ($object) {
            return round($object['SPM02'], 5);
        }, $data);
        $SPM03 = array_map(function ($object) {
            return round($object['SPM03'], 5);
        }, $data);
        $FLOWIN = array_map(function ($object) {
            return round($object['FLOWIN'], 5);
        }, $data);
        return array("SPP" => $SPP, "CSGP" => $CSGP, "SPM01" => $SPM01, "SPM02" => $SPM02, "SPM03" => $SPM03, "FLOWIN" => $FLOWIN);
    }

    private function getMudChartData($data)
    {
        $PITACTIVE = array_map(function ($object) {
            return round($object['PITACTIVE'], 5);
        }, $data);
        $FLOWOUTP = array_map(function ($object) {
            return round($object['FLOWOUTP'], 5);
        }, $data);
        $TGAS = array_map(function ($object) {
            return round($object['TGAS'], 5);
        }, $data);
        return array("PITACTIVE" => $PITACTIVE, "FLOWOUTP" => $FLOWOUTP, "TGAS" => $TGAS);
    }

    private function getDisplayData($data, $tags)
    {
        $drillingChartData = $this->getDrillingChartData($data);
        $pressureChartData = $this->getPressureChartData($data);
        $mudChartData = $this->getMudChartData($data);

        return ['drillingParameters' => $drillingChartData, 'pressureParameters' => $pressureChartData, 'mudParameters' => $mudChartData, 'tags' => $tags];
    }

    private function getLabels($data)
    {
        $tags = [];
        if ($data) {
            foreach ($data as $d) {
                $str = $d['datetime'];
                $time = substr($str, 11, 5);
                array_push($tags, $time);
            }
        }
        return $tags;
    }
}
