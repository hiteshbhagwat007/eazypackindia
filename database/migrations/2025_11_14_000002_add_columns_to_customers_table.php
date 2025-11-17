<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'email')) {
                $table->string('email')->nullable()->after('customer_name');
            }
            if (!Schema::hasColumn('customers', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('customers', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('customers', 'city')) {
                $table->string('city')->nullable()->after('address');
            }
            if (!Schema::hasColumn('customers', 'state')) {
                $table->string('state')->nullable()->after('city');
            }
            if (!Schema::hasColumn('customers', 'pincode')) {
                $table->string('pincode')->nullable()->after('state');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'pincode')) {
                $table->dropColumn('pincode');
            }
            if (Schema::hasColumn('customers', 'state')) {
                $table->dropColumn('state');
            }
            if (Schema::hasColumn('customers', 'city')) {
                $table->dropColumn('city');
            }
            if (Schema::hasColumn('customers', 'address')) {
                $table->dropColumn('address');
            }
            if (Schema::hasColumn('customers', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('customers', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
};

