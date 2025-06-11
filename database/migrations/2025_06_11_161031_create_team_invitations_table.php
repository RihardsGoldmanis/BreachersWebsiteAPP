<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('team_invitations', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('team_id');
        $table->string('invitee_email');
        $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
        $table->timestamps();

        $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_invitations');
    }
};
