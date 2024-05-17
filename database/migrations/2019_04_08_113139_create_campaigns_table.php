<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->uuid('uuid')
                ->comment('Identyfikator kampanii rekrutacyjnej.')
                ->primary()
                ->index();
            $table->string('name')
                ->comment('Nazwa kampanii rekrutacyjnej.');
            $table->string('short_description')
                ->comment('Krótki opis kampanii.');
            $table->string('description')
                ->comment('Długi opis kampanii.');
            $table->boolean('available')
                ->comment('Dostępność składania podań.');
            $table->string('form')
                ->comment('Formularz rekrutacyjny dla tej kampanii, serializowany do JSONa.');
            $table->string('who_can_check')
                ->comment('Lista identyfikatorów Discord osób z uprawnieniami do sprawdzania podań.');
            $table->bigInteger('ips_group_on_accept')
                ->nullable()
                ->comment('Identyfikator grupy na forum, która zostanie nadana po akceptacji podania.');
            $table->bigInteger('discord_role_on_accept')
                ->nullable()
                ->comment('Identyfikator roli na Discordzie, która zostanie nadana po akceptacji podania.');

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
        Schema::dropIfExists('campaigns');
    }
}
