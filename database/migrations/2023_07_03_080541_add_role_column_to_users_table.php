<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->nullable();
            $table->string('avatar')->nullable();
        });

        $user = User::create([
            'name' => 'Admin',
            'email' => 'jinhe012992@gmail.com',
            'password' => Hash::make('vstar105*!'),
        ]);

        $user = User::find($user->id);
        $user->role = 'admin';
        $user->avatar = 'admin_avatar.png';
        $user->save();


        $user = User::create([
            'name' => 'malaysia123',
            'email' => 'maraysia123123123@gmail.com',
            'password' => Hash::make('MAlAYmalay@123123'),
        ]);

        $user = User::find($user->id);
        $user->role = 'admin';
        $user->avatar = 'admin_avatar.png';
        $user->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};