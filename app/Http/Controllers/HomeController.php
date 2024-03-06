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
        return view('navbar.index');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function historyData_($startDate, $endDate)
    {
        $startDate = str_replace('*', ' ', $startDate);
        $endDate = str_replace('*', ' ', $endDate);

        if (!$startDate) {
            $endDate = date("Y-m-d H:i:s");
            $startDate = date('Y-m-d H:i:s', strtotime('-24 hour', strtotime($endDate)));
        }

        $chartData = ChartData::whereBetween('datetime', [$startDate, $endDate])
            ->orderBy('datetime', 'DESC')
            ->limit(2000)
            ->get();

        if (count($chartData) > 0) {
            $chartData = $chartData->toArray();
            $chartData = array_reverse($chartData);
        }

        $tags = $this->getTags($chartData, $endDate);
        $display_data = $this->getDisplayData($chartData, $tags);
        echo json_encode($display_data);
    }

    public function historyData()
    {
        $end_datetime = date("Y-m-d H:i:s");
        $start_datetime = date('Y-m-d H:i:s', strtotime('-24 hour', strtotime($end_datetime)));

        $chartData = ChartData::whereBetween('datetime', [$start_datetime, $end_datetime])
            ->orderBy('datetime', 'DESC')
            ->limit(2000)
            ->get();
        if (count($chartData) > 0) {
            $chartData = $chartData->toArray();
            $chartData = array_reverse($chartData);
        }

        $tags = $this->getTags($chartData, $end_datetime);
        $display_data = $this->getDisplayData($chartData, $tags);
        echo json_encode($display_data);
    }

    public function liveData()
    {
        $end_datetime = date("Y-m-d H:i:s");
        $start_datetime = date('Y-m-d H:i:s', strtotime('-30 minute', strtotime($end_datetime)));

        $chartData = ChartData::whereBetween('datetime', [$start_datetime, $end_datetime])
            ->orderBy('datetime', 'DESC')
            ->get();

        if (count($chartData) > 0) {
            $chartData = $chartData->toArray();
            $chartData = array_reverse($chartData);
        }

        $tags = $this->getTags($chartData, $end_datetime);
        $display_data = $this->getDisplayData($chartData, $tags);
        echo json_encode($display_data);
    }

    private function getDrillingChartData($data)
    {
        if (count($data) > 0) {
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
        } else {
            $SURFRPM = [0, 0, 0, 0, 0, 0];
            $WOB = [0, 0, 0, 0, 0, 0];
            $BITRPM = [0, 0, 0, 0, 0, 0];
            $TORQ = [0, 0, 0, 0, 0, 0];
            $BLKPOSCOMP = [0, 0, 0, 0, 0, 0];
            $HKLD = [0, 0, 0, 0, 0, 0];
        }

        $colors = array(
            "SURFRPM" => "#e3d1d1",
            "WOB" => "#c49493",
            "BITRPM" => "#593130",
            "TORQ" => "#c2d1c4",
            "BLKPOSCOMP" => "#9fbfa3",
            "HKLD" => "#456349"
        );

        $dataSet = array(
            "SURFRPM" => $SURFRPM,
            "WOB" => $WOB,
            "BITRPM" => $BITRPM,
            "TORQ" => $TORQ,
            "BLKPOSCOMP" => $BLKPOSCOMP,
            "HKLD" => $HKLD
        );

        $options = array(
            "min" => 0,
            "max" => 500
        );

        return ['dataSet' => $dataSet, 'colors' => $colors, 'options' => $options];
    }

    private function getPressureChartData($data)
    {
        if (count($data) > 0) {
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
        } else {
            $SPP = [0, 0, 0, 0, 0, 0];
            $CSGP = [0, 0, 0, 0, 0, 0];
            $SPM01 = [0, 0, 0, 0, 0, 0];
            $SPM02 = [0, 0, 0, 0, 0, 0];
            $SPM03 = [0, 0, 0, 0, 0, 0];
            $FLOWIN = [0, 0, 0, 0, 0, 0];
        }

        $colors = array(
            "SPP" => "#41914c",
            "CSGP" => "#42ad50",
            "SPM01" => "#90e09a",
            "SPM02" => "#7e9e82",
            "SPM03" => "#5f8564",
            "FLOWIN" => "#3a523d"
        );

        $dataSet = array(
            "SPP" => $SPP,
            "CSGP" => $CSGP,
            "SPM01" => $SPM01,
            "SPM02" => $SPM02,
            "SPM03" => $SPM03,
            "FLOWIN" => $FLOWIN,
            "colors" => $colors
        );

        $options = array(
            "min" => 0,
            "max" => 5000
        );

        return ['dataSet' => $dataSet, 'colors' => $colors, 'options' => $options];
    }

    private function getMudChartData($data)
    {
        if (count($data) > 0) {
            $PITACTIVE = array_map(function ($object) {
                return round($object['PITACTIVE'], 5);
            }, $data);
            $FLOWOUTP = array_map(function ($object) {
                return round($object['FLOWOUTP'], 5);
            }, $data);
            $TGAS = array_map(function ($object) {
                return round($object['TGAS'], 5);
            }, $data);
        } else {
            $PITACTIVE = [0, 0, 0, 0, 0, 0];
            $FLOWOUTP = [0, 0, 0, 0, 0, 0];
            $TGAS = [0, 0, 0, 0, 0, 0];
        }

        $colors = array(
            "PITACTIVE" => "#d3e1f5",
            "FLOWOUTP" => "#a5bbd9",
            "TGAS" => "#bac3cf"
        );

        $dataSet = array(
            "PITACTIVE" => $PITACTIVE,
            "FLOWOUTP" => $FLOWOUTP,
            "TGAS" => $TGAS,
            "colors" => $colors
        );

        $options = array(
            "min" => 0,
            "max" => 600
        );

        return ['dataSet' => $dataSet, 'colors' => $colors, 'options' => $options];
    }

    private function getDisplayData($data, $tags)
    {
        $drillingChartData = $this->getDrillingChartData($data);
        $pressureChartData = $this->getPressureChartData($data);
        $mudChartData = $this->getMudChartData($data);

        return ['drillingParameters' => $drillingChartData, 'pressureParameters' => $pressureChartData, 'mudParameters' => $mudChartData, 'tags' => $tags];
    }

    private function getTags($data, $endDate): array
    {
        $tags = [];
        if (count($data) > 0) {
            foreach ($data as $d) {
                $str = $d['datetime'];
                $time = substr($str, 11, 5);
                array_push($tags, $time);
            }
        } else {
            date_default_timezone_set("Asia/Tehran");

            if ($endDate) {
                $nowDate = date('H:i', strtotime($endDate));
            } else {
                $nowDate = date("H:i");
            }

            $date1 = date('H:i', strtotime('-5 minute', strtotime($nowDate)));
            $date2 = date('H:i', strtotime('-5 minute', strtotime($date1)));
            $date3 = date('H:i', strtotime('-5 minute', strtotime($date2)));
            $date4 = date('H:i', strtotime('-5 minute', strtotime($date3)));
            $date5 = date('H:i', strtotime('-5 minute', strtotime($date4)));

            array_push($tags, $nowDate);
            array_push($tags, $date1);
            array_push($tags, $date2);
            array_push($tags, $date3);
            array_push($tags, $date4);
            array_push($tags, $date5);

            $tags = array_reverse($tags);
        }
        return $tags;
    }
}
