<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('permissions') || ! Schema::hasTable('permission_groups')) {
            return;
        }

        if (! Schema::hasColumn('permissions', 'permission_group_id')) {
            Schema::table('permissions', function (Blueprint $table): void {
                $table->unsignedBigInteger('permission_group_id')->nullable()->after('id');
                $table->foreign('permission_group_id')->references('id')->on('permission_groups');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('permissions') || ! Schema::hasColumn('permissions', 'permission_group_id')) {
            return;
        }

        Schema::table('permissions', function (Blueprint $table): void {
            $table->dropForeign(['permission_group_id']);
            $table->dropColumn('permission_group_id');
        });
    }
};