<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chart3 extends Model
{
    use HasFactory;
    protected $fillable=['id','date','time','PITACTIVE','FLOWOUTP','TGAS'];
}
