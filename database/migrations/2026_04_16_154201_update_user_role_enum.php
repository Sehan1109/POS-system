<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('manager', 'cashier') NOT NULL DEFAULT 'cashier'");
        DB::statement("UPDATE users SET role = 'manager' WHERE role = 'admin'");
    }

    public function down(): void
    {
        DB::statement("UPDATE users SET role = 'admin' WHERE role = 'manager'");
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'cashier') NOT NULL DEFAULT 'cashier'");
    }
};
