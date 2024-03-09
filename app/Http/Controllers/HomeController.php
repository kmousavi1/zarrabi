<?php

namespace App\Http\Controllers;

use App\Models\Chart1;
use App\Models\Chart2;
use App\Models\Chart3;
use App\Models\ChartData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function getChartData($startDate, $endDate)
    {
        $bindings = [
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        return DB::select('
        WITH T AS (
        SELECT
            ROW_NUMBER() OVER(PARTITION BY CAST(`datetime` AS DATE), HOUR(`datetime`), MINUTE(`datetime`) ORDER BY `datetime` DESC) AS RW,`datetime`,
            SURFRPM, WOB, BITRPM, TORQ, BLKPOSCOMP, HKLD,
            SPP, CSGP, SPM01, SPM02, SPM03, FLOWIN,
            PITACTIVE, FLOWOUTP, TGAS FROM `chartdata`
            WHERE `datetime` BETWEEN :startDate AND :endDate
            ORDER BY `datetime` DESC
        )
        SELECT * FROM T WHERE RW = 1;
        ', $bindings);
    }

    public function historyData_($startDate, $endDate)
    {
        $startDate = str_replace('*', ' ', $startDate);
        $endDate = str_replace('*', ' ', $endDate);

        if (!$startDate) {
            $endDate = date("Y-m-d H:i:s");
            $startDate = date('Y-m-d H:i:s', strtotime('-24 hour', strtotime($endDate)));
        }

        /*$chartData = ChartData::whereBetween('datetime', [$startDate, $endDate])
                    ->orderBy('datetime', 'DESC')
                    ->get();*/

        $chartData = $this->getChartData($startDate, $endDate);

        if (count($chartData) > 0) {
//            $chartData = $chartData->toArray();
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

        /*$chartData = ChartData::whereBetween('datetime', [$start_datetime, $end_datetime])
            ->orderBy('datetime', 'DESC')
            ->get();*/

        $chartData = $this->getChartData($start_datetime, $end_datetime);

        if (count($chartData) > 0) {
//            $chartData = $chartData->toArray();
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

        /*$chartData = ChartData::whereBetween('datetime', [$start_datetime, $end_datetime])
            ->orderBy('datetime', 'DESC')
            ->get();*/

        $chartData = $this->getChartData($start_datetime, $end_datetime);

        if (count($chartData) > 0) {
//            $chartData = $chartData->toArray();
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
                $value = round($object->SURFRPM, 5);
                if ($value > 150) {
                    $value = 150;
                }
                return $value;
            }, $data);
            $WOB = array_map(function ($object) {
                $value = round($object->WOB, 5);
                if ($value > 70) {
                    $value = 70;
                }
                return $value;
            }, $data);
            $BITRPM = array_map(function ($object) {
                $value = round($object->BITRPM, 5);
                if ($value > 300) {
                    $value = 300;
                }
                return $value;
            }, $data);
            $TORQ = array_map(function ($object) {
                $value = round($object->TORQ, 5);
                if ($value > 60) {
                    $value = 60;
                }
                return $value;
            }, $data);
            $BLKPOSCOMP = array_map(function ($object) {
                $value = round($object->BLKPOSCOMP, 5);
                if ($value > 40) {
                    $value = 40;
                }
                return $value;
            }, $data);
            $HKLD = array_map(function ($object) {
                $value = round($object->HKLD, 5);
                if ($value > 500) {
                    $value = 500;
                }
                return $value;
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
            "SURFRPM" => "#16E2F5",
            "WOB" => "#FFA500",
            "BITRPM" => "#A747AC7",
            "TORQ" => "#FF0000",
            "BLKPOSCOMP" => "#040720",
            "HKLD" => "#08A04B"
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
                $value = round($object->SPP, 5);
                if ($value > 3000) {
                    $value = 3000;
                }
                return $value;
            }, $data);
            $CSGP = array_map(function ($object) {
                $value = round($object->CSGP, 5);
                if ($value > 5000) {
                    $value = 5000;
                }
                return $value;
            }, $data);
            $SPM01 = array_map(function ($object) {
                $value = round($object->SPM01, 5);
                if ($value > 70) {
                    $value = 70;
                }
                return $value;
            }, $data);
            $SPM02 = array_map(function ($object) {
                $value = round($object->SPM02, 5);
                if ($value > 70) {
                    $value = 70;
                }
                return $value;
            }, $data);
            $SPM03 = array_map(function ($object) {
                $value = round($object->SPM03, 5);
                if ($value > 70) {
                    $value = 70;
                }
                return $value;
            }, $data);
            $FLOWIN = array_map(function ($object) {
                $value = round($object->FLOWIN, 5);
                if ($value > 1000) {
                    $value = 1000;
                }
                return $value;
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
            "SPP" => "#16E2F5",
            "CSGP" => "#FFA500",
            "SPM01" => "#A74AC7",
            "SPM02" => "#FF0000",
            "SPM03" => "#040720",
            "FLOWIN" => "#08A04B"
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
                $value = round($object->PITACTIVE, 5);
                if ($value > 600) {
                    $value = 600;
                }
                return $value;
            }, $data);
            $FLOWOUTP = array_map(function ($object) {
                $value = round($object->FLOWOUTP, 5);
                if ($value > 100) {
                    $value = 100;
                }
                return $value;
            }, $data);
            $TGAS = array_map(function ($object) {
                $value = round($object->TGAS, 5);
                if ($value > 100) {
                    $value = 100;
                }
                return $value;
            }, $data);
        } else {
            $PITACTIVE = [0, 0, 0, 0, 0, 0];
            $FLOWOUTP = [0, 0, 0, 0, 0, 0];
            $TGAS = [0, 0, 0, 0, 0, 0];
        }

        $colors = array(
            "PITACTIVE" => "#16E2F5",
            "FLOWOUTP" => "#040720",
            "TGAS" => "#08A04F"
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
        $dataCount = count($data);
        $tags = [];
        if ($dataCount > 0) {
            foreach ($data as $d) {
                $str = $d->datetime;
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

    public function getLatestData()
    {
        $chartData = ChartData::orderBy('datetime', 'DESC')
            ->first();

        $chartData["DEPTVD"] = round($chartData["DEPTVD"], 5);
        if ($chartData["DEPTVD"] < 0)
            $chartData["DEPTVD"] = 0;
        $chartData["DEPBITTVD"] = round($chartData["DEPBITTVD"], 5);
        if ($chartData["DEPBITTVD"] < 0)
            $chartData["DEPBITTVD"] = 0;
        $chartData["HKLD"] = round($chartData["HKLD"], 5);
        if ($chartData["HKLD"] < 0)
            $chartData["HKLD"] = 0;
        $chartData["WOB"] = round($chartData["WOB"], 5);
        if ($chartData["WOB"] < 0)
            $chartData["WOB"] = 0;
        $chartData["ROPA"] = round($chartData["ROPA"], 5);
        if ($chartData["ROPA"] < 0)
            $chartData["ROPA"] = 0;
        $chartData["SURFRPM"] = round($chartData["SURFRPM"], 5);
        if ($chartData["SURFRPM"] < 0)
            $chartData["SURFRPM"] = 0;
        $chartData["TORQ"] = round($chartData["TORQ"], 5);
        if ($chartData["TORQ"] < 0)
            $chartData["TORQ"] = 0;
        $chartData["SPP"] = round($chartData["SPP"], 5);
        if ($chartData["SPP"] < 0)
            $chartData["SPP"] = 0;
        $chartData["TGAS"] = round($chartData["TGAS"], 5);
        if ($chartData["TGAS"] < 0)
            $chartData["TGAS"] = 0;

        echo json_encode($chartData);
    }
}
