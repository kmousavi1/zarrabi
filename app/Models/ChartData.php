<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartData extends Model
{
    use HasFactory;

    protected $table = "chartdata";

    protected $fillable=['id','date','time','datetime','BLKPOSCOMP','HKLD','WOB','TORQ','SURFRPM','BITRPM',
        'SPP','CSGP','SPM01','SPM02','SPM03','FLOWIN',
        'PITACTIVE','FLOWOUTP','TGAS'];
}
