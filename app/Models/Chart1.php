<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chart1 extends Model
{
    use HasFactory;
    protected $fillable=['id','date','time','BLKPOSCOMP','HKLD','WOB','TORQ','SURFRPM','BITRPM'];
}
