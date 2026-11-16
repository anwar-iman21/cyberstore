<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_confirmations', function (Blueprint $table) {
            // README bagian U: payment_status TERPISAH dari order status
            $table->string('payment_method')->nullable()->after('order_id');
            $table->decimal('amount', 12, 2)->nullable()->after('payment_method');
            $table->enum('payment_status', ['unpaid', 'waiting_confirmation', 'paid', 'rejected'])
                ->default('unpaid')
                ->after('amount');
            $table->timestamp('paid_at')->nullable()->after('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('payment_confirmations', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'amount', 'payment_status', 'paid_at']);
        });
    }
};
