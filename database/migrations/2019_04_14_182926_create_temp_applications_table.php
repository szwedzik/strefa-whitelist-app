<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_applications', function (Blueprint $table) {
            $table->uuid('uuid');

            $table->string('age');
            $table->string('forum_name');
            $table->string('steam_url');

            $table->string('discord_id');

            $table->string('rules_opinion');
            $table->string('rp_definition');
            $table->string('past_characters');
            $table->string('character_idea');
            $table->string('streamer');
            $table->string('me_do');
            $table->string('ooc_vs_ic');
            $table->string('do_lying');
            $table->string('tweet');
            $table->string('revenge_kill');
            $table->string('brutally_wounded');
            $table->string('meta_gaming');
            $table->string('power_gaming');
            $table->string('forget');
            $table->string('crash');

            $table->integer('state');

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
        Schema::dropIfExists('temp_applications');
    }
}
