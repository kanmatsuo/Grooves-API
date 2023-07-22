<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ActionDescription;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('action_descriptions', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->timestamps();
        });

        $data = [
            [
                'description' => 'Minted NFT By Admin',
            ],
            ['description' => 'Created Listing By Admin'],
            ['description' => 'Created Auction By Admin'],
            ['description' => 'Updated Listing By Admin'],
            ['description' => 'Cancelled Listing By Admin'],
            ['description' => 'withdrawn No Bid NFT By Admin'],
            ['description' => 'Claimed Bid By Admin'],
            ['description' => 'Added To Gacha List By Admin'],
            ['description' => 'Removed from Gacha List By Admin'],
            ['description' => 'Bought By User'],
            ['description' => 'Won Auction By User'],
            ['description' => 'Made Bid By User'],
            ['description' => 'Played Gacha By User'],
            ['description' => 'Added To Burn Reward List By Admin'],
            ['description' => 'Removed From Burn Reward List By Admin'],
            ['description' => 'Burn By User'],
            ['description' => 'Reward To User'],
        ];

        ActionDescription::insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('action_descriptions');
    }
};