<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->uuid('uuid')
                ->comment('Identyfikator aplikacji.')
                ->primary()
                ->index();
            $table->unsignedBigInteger('discord_id')
                ->comment('ID użytkownika na Discordzie.');
            $table->string('ips_username')
                ->comment('Nazwa użytkownika na forum.')
                ->nullable();
            $table->string('steam_hex')
                ->comment('HEX wyciągnięty ze Steam ID aplikanta.')
                ->nullable();
            $table->string('body')
                ->comment('Pytania i odpowiedzi tej aplikacji, serializowane do JSONa.');
            $table->string('state')
                ->default('SENT')
                ->comment('Status aplikacji (SENT / REJECTED / ACCEPTED).');
            $table->boolean('prioritised')
                ->default(false)
                ->comment('Priorytet na sprawdzenie aplikacji.');
            $table->ipAddress('ip')
                ->comment('Adres IP, z którego wysłano aplikację.');
            $table->uuid('campaign_uuid')
                ->comment('Identyfikator kampanii rekrutacyjnej.');
            $table->uuid('payment_uuid')
                ->nullable()
                ->default(null)
                ->comment('Identyfikator płatności powiązanej z tym podaniem (cele przyspieszenia).');

            $table->timestamps();

            $table->foreign('campaign_uuid')->references('uuid')->on('campaigns');
            $table->foreign('payment_uuid')->references('uuid')->on('payments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applications');
    }
}
