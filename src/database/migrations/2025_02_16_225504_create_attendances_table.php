<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {

            /**追加ユーザーの出退勤の一覧のテーブル↓ */
            $table->id(); // ユニークID
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ユーザーとID番号を紐づけ（外部キー）
            $table->date('date'); // 出勤日
            $table->time('clock_in')->nullable(); // 出勤時間（null許容）
            $table->time('clock_out')->nullable(); // 退勤時間（null許容）
            $table->timestamps(); // 作成日時・更新日時
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
