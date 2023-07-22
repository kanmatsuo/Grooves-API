<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\StatusDescription;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_descriptions', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->timestamps();
        });

        $data = [
            [
                'description' => 'MINT',
            ],
            [
                'description' => 'LISTING',
            ],
            [
                'description' => 'AS LISTING',
            ],
            [
                'description' => 'AUCTION',
            ],
            [
                'description' => 'AS AUCTION',
            ],
            [
                'description' => 'GACHA',
            ],
            [
                'description' => 'AS GACHA',
            ],
            [
                'description' => 'BURN RAWARD',
            ],
            [
                'description' => 'AS BURN RAWARD',
            ],
            [
                'description' => 'BURN',
            ],
        ];

        StatusDescription::insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('status_descriptions');
    }
};