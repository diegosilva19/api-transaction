<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TrasactionTableMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('payee_id');
            $table->integer('payer_id');
            $table->decimal('amount', 15,2);
            $table->string('authorization_code', 37)->nullable();//->default(DB::raw('select UUID()'));
            $table->timestamp('date_transaction')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        DB::unprepared('CREATE TRIGGER before_insert_transaction_generate_authorization
                            BEFORE INSERT ON transaction
                            FOR EACH ROW
                            SET new.authorization_code = uuid();');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction');
        DB::unprepared('DROP TRIGGER before_insert_transaction_generate_authorization');
    }
}
