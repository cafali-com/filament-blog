<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {

        Schema::create(config('filamentblog.tables.prefix').'articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
        });
    }

    public function down()
    {
        Schema::dropIfExists(config('filamentblog.tables.prefix').'articles');
    }
};
