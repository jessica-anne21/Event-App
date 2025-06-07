<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date');
            $table->time('time');
            $table->string('location');
            $table->string('speaker');
            $table->string('poster_path')->nullable();
            $table->decimal('registration_fee', 10, 2)->default(0.00);
            $table->integer('max_participants')->nullable();
            $table->text('description')->nullable(); // Deskripsi event
            $table->foreignId('created_by')->constrained('users'); // Foreign key ke user yang membuat
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
        Schema::dropIfExists('events');
    }
}