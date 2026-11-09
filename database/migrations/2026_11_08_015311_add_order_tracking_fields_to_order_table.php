
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order', function (Blueprint $table) {
            if (!Schema::hasColumn('order', 'order_status')) {
                $table->string('order_status')->default('Pending');
            }

            if (!Schema::hasColumn('order', 'tracking_number')) {
                $table->string('tracking_number')->nullable();
            }

            if (!Schema::hasColumn('order', 'shipped_at')) {
                $table->timestamp('shipped_at')->nullable();
            }

            if (!Schema::hasColumn('order', 'delivered_at')) {
                $table->timestamp('delivered_at')->nullable();
            }

            if (!Schema::hasColumn('order', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('order', function (Blueprint $table) {
            $columns = [
                'order_status',
                'tracking_number',
                'shipped_at',
                'delivered_at',
                'cancelled_at',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('order', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
