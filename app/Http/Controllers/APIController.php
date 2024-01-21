<?php

namespace App\Http\Controllers;

use App\Models\Chart1;
use App\Models\Chart2;
use App\Models\Chart3;
use App\Models\ChartData;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isFalse;
use function PHPUnit\Framework\isNull;

class APIController extends Controller
{

    public function last_id()
    {
        /*$last_id_chart1 = Chart1::orderBy('id', 'DESC')->get();
        if ($last_id_chart1->count() != 0) {
            $last_id_chart1 = $last_id_chart1->pluck('id')[0];
        } else {
            $last_id_chart1 = 0;
        }
        $last_id_chart2 = Chart2::orderBy('id', 'DESC')->get();
        if ($last_id_chart2->count() != 0) {
            $last_id_chart2 = $last_id_chart2->pluck('id')[0];
        } else {
            $last_id_chart2 = 0;
        }
        $last_id_chart3 = Chart3::orderBy('id', 'DESC')->get();
        if ($last_id_chart3->count() != 0) {
            $last_id_chart3 = $last_id_chart3->pluck('id')[0];
        } else {
            $last_id_chart3 = 0;
        }*/

        $last_id = ChartData::orderBy('id', 'DESC')->get();
        if ($last_id->count() != 0) {
            $last_id = $last_id->pluck('id')[0];
        } else {
            $last_id = 0;
        }

        $data = [
            'status' => true,
            'message' => 'شناسه آخر با موفقیت خوانده شد',
            'last_id' => $last_id
        ];
        $result = response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);

        return $result;
    }

    public function save_data(Request $request)
    {

//    return json_encode((string)$request['chart1_data']['id'][0]);


        $chartData = $request['data'];

        if ($chartData) {
            for ($i = 0; $i < count($chartData['id']); $i++) {
                $chart = new ChartData();
                $chart->id = $chartData['id'][$i];
                $chart->BLKPOSCOMP = $chartData['BLKPOSCOMP'][$i];
                $chart->HKLD = $chartData['HKLD'][$i];
                $chart->WOB = $chartData['WOB'][$i];
                $chart->TORQ = $chartData['TORQ'][$i];
                $chart->SURFRPM = $chartData['SURFRPM'][$i];
                $chart->BITRPM = $chartData['BITRPM'][$i];

                $chart->SPP = $chartData['SPP'][$i];
                $chart->CSGP = $chartData['CSGP'][$i];
                $chart->SPM01 = $chartData['SPM01'][$i];
                $chart->SPM02 = $chartData['SPM02'][$i];
                $chart->SPM03 = $chartData['SPM03'][$i];
                $chart->FLOWIN = $chartData['FLOWIN'][$i];

                $chart->PITACTIVE = $chartData['PITACTIVE'][$i];
                $chart->FLOWOUTP = $chartData['FLOWOUTP'][$i];
                $chart->TGAS = $chartData['TGAS'][$i];

                try {
                    $chart->save();
                } catch (\Exception $e) {
                }
            }
        }

        /*
                for ($i = 0; $i < count($chart2_data['id']); $i++) {
                    $chart2 = new Chart2();
                    $chart2->id = $chart2_data['id'][$i];
                    $chart2->SPP = $chart2_data['SPP'][$i];
                    $chart2->CSGP = $chart2_data['CSGP'][$i];
                    $chart2->SPM01 = $chart2_data['SPM01'][$i];
                    $chart2->SPM02 = $chart2_data['SPM02'][$i];
                    $chart2->SPM03 = $chart2_data['SPM03'][$i];
                    $chart2->FLOWIN = $chart2_data['FLOWIN'][$i];
                    try {
                        $chart2->save();
                    } catch (\Exception $e) {
                    }
                }


                for ($i = 0; $i < count($chart3_data['id']); $i++) {
                    $chart3 = new Chart3();
                    $chart3->id = $chart3_data['id'][$i];
                    $chart3->PITACTIVE = $chart3_data['PITACTIVE'][$i];
                    $chart3->FLOWOUTP = $chart3_data['FLOWOUTP'][$i];
                    $chart3->TGAS = $chart3_data['TGAS'][$i];
                    try {
                        $chart3->save();
                    } catch (\Exception $e) {
                    }

                }*/

        $data = [
            'status' => true,
            'message' => 'اطلاعات با موفقیت ثبت شد',
        ];
        $result = response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);

        return $result;

    }


}











