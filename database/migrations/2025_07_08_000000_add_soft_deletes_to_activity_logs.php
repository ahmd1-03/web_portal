<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeletesToActivityLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->softDeletes();
            $table->unsignedBigInteger('user_id')->nullable()->after('details');
            $table->string('ip_address', 45)->nullable()->after('user_id');
            $table->text('user_agent')->nullable()->after('ip_address');
            $table->json('old_values')->nullable()->after('user_agent');
            $table->json('new_values')->nullable()->after('old_values');
            $table->index(['type', 'action']);
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn(['user_id', 'ip_address', 'user_agent', 'old_values', 'new_values']);
            $table->dropIndex(['type', 'action']);
            $table->dropIndex(['deleted_at']);
        });
    }
}
