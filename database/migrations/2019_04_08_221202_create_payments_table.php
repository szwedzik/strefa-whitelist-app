<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('uuid')
                ->comment('Identyfikator płatności.')
                ->primary()
                ->index();
            $table->string('paypal_id')
                ->comment('Identyfikator płatności PayPal.');
            $table->unsignedBigInteger('discord_id')
                ->comment('ID płacącego na Discordzie.');
            $table->string('full_name')
                ->comment('Imię i nazwisko płatnika.');
            $table->string('email')
                ->comment('Adres e-mail, z którego odnotowano płatność.');
            $table->ipAddress('ip')
                ->comment('Adres IP, z którego odnotowano płatność.');
            $table->unsignedInteger('amount')
                ->comment('Ilość zapłaconych pieniędzy.');
            $table->string('currency')
                ->comment('Waluta płatności.');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
