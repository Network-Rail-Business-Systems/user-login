<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $nameSql =
                DB::getDefaultConnection() === 'sqlite'
                    ? 'first_name || " " || last_name'
                    : 'CONCAT(first_name, " ", last_name)';

            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('name')->storedAs($nameSql);
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table
                ->string('guid')
                ->unique()
                ->nullable();
            $table->string('domain')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}