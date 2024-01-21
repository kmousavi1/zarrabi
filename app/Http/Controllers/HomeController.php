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

        $data_chart = ChartData::whereBetween('datetime', [$start_datetime, $end_datetime])->get();

        if (count($data_chart) > 0) {

            $data_chart1 = $this->getDrillingData($data_chart);
            $data_chart2 = $this->getPressureData($data_chart);
            $data_chart3 = $this->getMudData($data_chart);

            $display_data = $this->getDisplayData($data_chart1, $data_chart2, $data_chart3);
            echo json_encode($display_data);
        } else {
            echo json_encode($display_data);
        }
    }

    public function display_data_live()
    {

        $display_data = [];
        $limit_size = 10;

        $data_chart = ChartData::orderBy('id', 'DESC')->limit($limit_size)->get();

        if (count($data_chart) > 0) {

            $data_chart1 = $this->getDrillingData($data_chart);
            $data_chart2 = $this->getPressureData($data_chart);
            $data_chart3 = $this->getMudData($data_chart);

            $display_data = $this->getDisplayData($data_chart1, $data_chart2, $data_chart3);
            echo json_encode($display_data);
        } else {
            echo json_encode($display_data);
        }
    }

    private function getDrillingData($data_chart){
        $BLKPOSCOMP = $data_chart->pluck('BLKPOSCOMP ');
        $HKLD = $data_chart->pluck('HKLD');
        $WOB = $data_chart->pluck('WOB');
        $TORQ = $data_chart->pluck('TORQ');
        $SURFRPM = $data_chart->pluck('SURFRPM');
        $BITRPM = $data_chart->pluck('BITRPM');
        return ['BLKPOSCOMP' => $BLKPOSCOMP, 'HKLD' => $HKLD, 'WOB' => $WOB, 'TORQ' => $TORQ,
            'SURFRPM' => $SURFRPM, 'BITRPM' => $BITRPM];
    }

    private function getPressureData($data_chart){
        $SPP = $data_chart->pluck('SPP ');
        $CSGP = $data_chart->pluck('CSGP');
        $SPM01 = $data_chart->pluck('SPM01');
        $SPM02 = $data_chart->pluck('SPM02');
        $SPM03 = $data_chart->pluck('SPM03');
        $FLOWIN = $data_chart->pluck('FLOWIN');
        return ['SPP' => $SPP, 'CSGP' => $CSGP, 'SPM01' => $SPM01, 'SPM02' => $SPM02,
            'SPM03' => $SPM03, 'FLOWIN' => $FLOWIN];
    }

    private function getMudData($data_chart){
        $PITACTIVE = $data_chart->pluck('PITACTIVE ');
        $FLOWOUTP = $data_chart->pluck('FLOWOUTP');
        $TGAS = $data_chart->pluck('TGAS');
        return ['PITACTIVE' => $PITACTIVE, 'FLOWOUTP' => $FLOWOUTP, 'TGAS' => $TGAS];
    }

    private function getDisplayData($data_chart1, $data_chart2, $data_chart3)
    {
        $tags = $this->getLabels($data_chart1);

        $drillingChartData = $this->getDrillingChartData($data_chart1);
        $pressureChartData = $this->getPressureChartData($data_chart2);
        $mudChartData = $this->getMudChartData($data_chart3);

        return ['drillingParameters' => $drillingChartData, 'pressureParameters' => $pressureChartData, 'mudParameters' => $mudChartData, 'tags' => $tags];
    }

    private function getLabels($data)
    {
        $tags = [];
        $len = count($data);
        if ($len > 0) {
            for ($i = 0; $i < $len; $i++) {
                $str = $data[$i]['datetime'];
                $time = substr($str, 11, 5);
                array_push($tags, $time);
            }
        }
        return $tags;
    }

    private function getDrillingChartData($data)
    {
        $SURFRPM = array_map(function ($object) {
            return $object['SURFRPM'];
        }, $data);
        $WOB = array_map(function ($object) {
            return $object['WOB'];
        }, $data);
        $BITRPM = array_map(function ($object) {
            return $object['BITRPM'];
        }, $data);
        $TORQ = array_map(function ($object) {
            return $object['TORQ'];
        }, $data);
        $BLKPOSCOMP = array_map(function ($object) {
            return $object['BLKPOSCOMP'];
        }, $data);
        $HKLD = array_map(function ($object) {
            return $object['HKLD'];
        }, $data);
        return array("SURFRPM" => $SURFRPM, "WOB" => $WOB, "BITRPM" => $BITRPM, "TORQ" => $TORQ, "BLKPOSCOMP" => $BLKPOSCOMP, "HKLD" => $HKLD);
    }

    private function getPressureChartData($data)
    {
        $SPP = array_map(function ($object) {
            return $object['SPP'];
        }, $data);
        $CSGP = array_map(function ($object) {
            return $object['CSGP'];
        }, $data);
        $SPM01 = array_map(function ($object) {
            return $object['SPM01'];
        }, $data);
        $SPM02 = array_map(function ($object) {
            return $object['SPM02'];
        }, $data);
        $SPM03 = array_map(function ($object) {
            return $object['SPM03'];
        }, $data);
        $FLOWIN = array_map(function ($object) {
            return $object['FLOWIN'];
        }, $data);
        return array("SPP" => $SPP, "CSGP" => $CSGP, "SPM01" => $SPM01, "SPM02" => $SPM02, "SPM03" => $SPM03, "FLOWIN" => $FLOWIN);
    }

    private function getMudChartData($data)
    {
        $PITACTIVE = array_map(function ($object) {
            return $object['PITACTIVE'];
        }, $data);
        $FLOWOUTP = array_map(function ($object) {
            return $object['FLOWOUTP'];
        }, $data);
        $TGAS = array_map(function ($object) {
            return $object['TGAS'];
        }, $data);
        return array("PITACTIVE" => $PITACTIVE, "FLOWOUTP" => $FLOWOUTP, "TGAS" => $TGAS);
    }
}
