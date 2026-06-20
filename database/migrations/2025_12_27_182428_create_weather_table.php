<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('weather', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained()->onDelete('cascade');
            $table->decimal('temperature', 5, 2)->comment('درجة الحرارة بالسيليسيوس');
            $table->decimal('feels_like', 5, 2)->nullable()->comment('الشعور بدرجة الحرارة');
            $table->integer('humidity')->nullable()->comment('الرطوبة %');
            $table->decimal('wind_speed', 5, 2)->nullable()->comment('سرعة الرياح m/s');
            $table->integer('wind_degree')->nullable()->comment('اتجاه الرياح بالدرجات');
            $table->integer('pressure')->nullable()->comment('الضغط الجوي hPa');
            $table->integer('visibility')->nullable()->comment('الرؤية بالأمتار');
            $table->integer('clouds')->nullable()->comment('الغيوم %');
            $table->string('condition')->nullable()->comment('حالة الطقس (clear, clouds, rain, etc.)');
            $table->string('description')->nullable()->comment('وصف الطقس');
            $table->string('icon')->nullable()->comment('رمز الأيقونة');
            $table->date('date')->comment('تاريخ السجل');
            $table->time('time')->nullable()->comment('وقت السجل');
            $table->json('forecast')->nullable()->comment('التوقعات لـ 7 أيام');
            $table->json('alerts')->nullable()->comment('التحذيرات والتنبيهات');
            $table->timestamp('last_updated')->nullable()->comment('آخر تحديث');
            $table->timestamps();
            
            $table->index(['destination_id', 'date']);
            $table->unique(['destination_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather');
    }
};
