<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionRegistrationsTable extends Migration
{
    public function up()
    {
        Schema::create('session_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('registrations')->onDelete('cascade');
            $table->foreignId('sub_event_id')->constrained('sub_events')->onDelete('cascade');
            $table->boolean('attended_session')->default(false);
            $table->timestamps();

            $table->unique(['registration_id', 'sub_event_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('session_registrations');
    }
}