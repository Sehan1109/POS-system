<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete()->after('user_id');
            $table->string('invoice_number')->nullable()->unique()->after('customer_id');
            $table->decimal('tax_amount', 10, 2)->default(0)->after('discount');
            $table->enum('status', ['completed', 'refund_requested', 'refunded', 'cancelled'])->default('completed')->after('payment_method');
            $table->text('refund_reason')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn(['customer_id', 'invoice_number', 'tax_amount', 'status', 'refund_reason']);
        });
    }
};
