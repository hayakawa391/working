<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
{
    Schema::create('attendance_requests', function (Blueprint $table) {
        $table->id();

        // 外部キー：誰が申請したか
        $table->foreignId('user_id')->constrained()->onDelete('cascade');

        // 外部キー：どの勤怠に対する申請か
        $table->foreignId('attendance_id')->constrained()->onDelete('cascade');

        // 修正対象（例：clock_in, clock_out など）
        $table->string('target_column');

        // 元の値
        $table->string('old_value')->nullable();

        // 新しい申請値
        $table->string('new_value');

        // 申請理由
        $table->text('reason')->nullable();

        // ステータス（pending, approved, rejected）
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

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
        Schema::dropIfExists('attendance_requests');
    }
}
