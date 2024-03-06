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

        $last_id = ChartData::orderBy('id', 'DESC')
            ->limit(1)
            ->get();

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
        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function save_data(Request $request)
    {
        $chartDataResponse = $request['data'];

        if ($chartDataResponse) {
            date_default_timezone_set("Asia/Tehran");
            foreach ($chartDataResponse as $data){
                $chart = new ChartData();

                $chart->id = $data['id'];
                $chart->datetime = date("Y-m-d H:i:s");
                $chart->BLKPOSCOMP = $data['BLKPOSCOMP'];
                $chart->HKLD = $data['HKLD'];
                $chart->WOB = $data['WOB'];
                $chart->TORQ = $data['TORQ'];
                $chart->SURFRPM = $data['SURFRPM'];
                $chart->BITRPM = $data['BITRPM'];

                $chart->SPP = $data['SPP'];
                $chart->CSGP = $data['CSGP'];
                $chart->SPM01 = $data['SPM01'];
                $chart->SPM02 = $data['SPM02'];
                $chart->SPM03 = $data['SPM03'];
                $chart->FLOWIN = $data['FLOWIN'];

                $chart->PITACTIVE = $data['PITACTIVE'];
                $chart->FLOWOUTP = $data['FLOWOUTP'];
                $chart->TGAS = $data['TGAS'];

                try {
                    $chart->save();
                } catch (\Exception $e) {
                }
            }
        }

        $data = [
            'status' => true,
            'message' => 'اطلاعات با موفقیت ثبت شد',
        ];
        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
