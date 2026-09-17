<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('restaurant_name')->default('34 Lounge');
            $table->string('whatsapp_number')->default('+965 6666 1404');
            $table->string('location')->default('Darah Mall, Kuwait City');
            $table->string('currency')->default('KWD');
            $table->boolean('show_arabic')->default(true);
            $table->boolean('show_placeholder')->default(true);
            $table->boolean('enable_popups')->default(true);
            $table->boolean('enable_whatsapp_order')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
