<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chart1s', function (Blueprint $table) {
            $table->id();
            $table->datetime('datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->double('BLKPOSCOMP')->nullable();
            $table->double('HKLD')->nullable();
            $table->double('WOB')->nullable();
            $table->double('TORQ')->nullable();
            $table->double('SURFRPM')->nullable();
            $table->double('BITRPM')->nullable();
            $table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chart1');
    }
};
