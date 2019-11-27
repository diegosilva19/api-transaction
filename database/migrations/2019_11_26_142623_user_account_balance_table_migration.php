<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserAccountBalanceTableMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_account_balance', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user');
            $table->enum('type_user_account', ['seller', 'consumer']);
            $table->decimal('amount', 15, 2)->default('0.00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_account_balance');
    }
}
