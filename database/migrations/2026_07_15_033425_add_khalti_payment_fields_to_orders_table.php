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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->default('khalti')->after('status');
            $table->string('payment_status')->default('unpaid')->index()->after('payment_method');
            $table->string('khalti_pidx')->nullable()->unique()->after('payment_status');
            $table->string('khalti_transaction_id')->nullable()->after('khalti_pidx');
            $table->unsignedInteger('khalti_amount')->nullable()->after('khalti_transaction_id');
            $table->timestamp('paid_at')->nullable()->after('khalti_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['khalti_pidx']);

            $table->dropColumn([
                'payment_method',
                'payment_status',
                'khalti_pidx',
                'khalti_transaction_id',
                'khalti_amount',
                'paid_at',
            ]);
        });
    }
};
