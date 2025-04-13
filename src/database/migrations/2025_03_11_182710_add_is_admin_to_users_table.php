<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsAdminToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //users テーブルに新しいカラム is_admin を追加し、このカラムを管理者かどうかを判定するために使用する
            $table->boolean('is_admin')->default(false)->after('password');
//　　　　　 ↑is_adminという名のカラム追加。デフォルトはfalse
// 　　　　　で、新規登録者は一般ユーザーとして扱われる。
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin'); // ロールバック時に削除
        });
    }
}
