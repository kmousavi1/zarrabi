<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chart2 extends Model
{
    use HasFactory;
    protected $fillable=['id','date','time','SPP','CSGP','SPM01','SPM02','SPM03','FLOWIN'];
}
