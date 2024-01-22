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

        if (!$start_datetime){
            $end_datetime = date("Y-m-d H:i:s");
            //add 1 hour to time
            $start_datetime = date('Y-m-d H:i:s', strtotime('-24 hour', strtotime($end_datetime)));
        }

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

    public function display_data_history_()
    {
        $display_data = [];

            $end_datetime = date("Y-m-d H:i:s");
            //add 1 hour to time
            $start_datetime = date('Y-m-d H:i:s', strtotime('-24 hour', strtotime($end_datetime)));

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

        $end_datetime = date("Y-m-d H:i:s");
        $start_datetime = date('Y-m-d H:i:s', strtotime('-1 minute', strtotime($end_datetime)));

        $chartData = ChartData::orderBy('id', 'DESC')->whereBetween('datetime', [$start_datetime, $end_datetime])->limit($limit_size)->get();

        if (count($chartData) > 0) {
            $chartData = $chartData->toArray();

            $tags = $this->getLabels($chartData);

            $display_data = $this->getDisplayData($chartData, $tags);
            echo json_encode($display_data);
        } else {
            $nowDate = date("H:i");
            $date1 = date('H:i', strtotime('-5 minute', strtotime($nowDate)));
            $date2 = date('H:i', strtotime('-5 minute', strtotime($date1)));
            $date3 = date('H:i', strtotime('-5 minute', strtotime($date2)));
            $date4 = date('H:i', strtotime('-5 minute', strtotime($date3)));
            $date5 = date('H:i', strtotime('-5 minute', strtotime($date4)));
            $date6 = date('H:i', strtotime('-5 minute', strtotime($date5)));
            $date7 = date('H:i', strtotime('-5 minute', strtotime($date6)));
            $date8 = date('H:i', strtotime('-5 minute', strtotime($date7)));
            $date9 = date('H:i', strtotime('-5 minute', strtotime($date8)));

            $tags = [];
            array_push($tags, $date9);
            array_push($tags, $date8);
            array_push($tags, $date7);
            array_push($tags, $date6);
            array_push($tags, $date5);
            array_push($tags, $date4);
            array_push($tags, $date3);
            array_push($tags, $date2);
            array_push($tags, $date1);
            array_push($tags, $nowDate);

            $display_data = ['drillingParameters' => [], 'pressureParameters' => [], 'mudParameters' => [], 'tags' => $tags];
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
