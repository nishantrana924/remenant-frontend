<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('warehouse_activity_logs', function (Blueprint $table) {
            if (Schema::hasColumns('warehouse_activity_logs', ['related_batch_id', 'action'])) {
                if (!Schema::hasIndex('warehouse_activity_logs', 'warehouse_activity_logs_related_batch_id_action_index')) {
                    $table->index(['related_batch_id', 'action']);
                }
            }
            
            if (Schema::hasColumn('warehouse_activity_logs', 'created_at')) {
                if (!Schema::hasIndex('warehouse_activity_logs', 'warehouse_activity_logs_created_at_index')) {
                    $table->index('created_at');
                }
            }
        });

        Schema::table('failed_jobs', function (Blueprint $table) {
            if (Schema::hasColumn('failed_jobs', 'queue') && !Schema::hasIndex('failed_jobs', 'failed_jobs_queue_index')) {
                DB::statement('CREATE INDEX failed_jobs_queue_index ON failed_jobs (queue(191))');
            }
            
            if (Schema::hasColumn('failed_jobs', 'failed_at') && !Schema::hasIndex('failed_jobs', 'failed_jobs_failed_at_index')) {
                $table->index('failed_at');
            }
        });

        Schema::table('warehouse_batches', function (Blueprint $table) {
            if (Schema::hasColumns('warehouse_batches', ['status', 'created_at'])) {
                if (!Schema::hasIndex('warehouse_batches', 'warehouse_batches_status_created_at_index')) {
                    $table->index(['status', 'created_at']);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('warehouse_activity_logs', function (Blueprint $table) {
            if (Schema::hasIndex('warehouse_activity_logs', 'warehouse_activity_logs_related_batch_id_action_index')) {
                $table->dropIndex('warehouse_activity_logs_related_batch_id_action_index');
            }
            if (Schema::hasIndex('warehouse_activity_logs', 'warehouse_activity_logs_created_at_index')) {
                $table->dropIndex('warehouse_activity_logs_created_at_index');
            }
        });

        Schema::table('failed_jobs', function (Blueprint $table) {
            if (Schema::hasIndex('failed_jobs', 'failed_jobs_queue_index')) {
                $table->dropIndex('failed_jobs_queue_index');
            }
            if (Schema::hasIndex('failed_jobs', 'failed_jobs_failed_at_index')) {
                $table->dropIndex('failed_jobs_failed_at_index');
            }
        });

        Schema::table('warehouse_batches', function (Blueprint $table) {
            if (Schema::hasIndex('warehouse_batches', 'warehouse_batches_status_created_at_index')) {
                $table->dropIndex('warehouse_batches_status_created_at_index');
            }
        });
    }
};
