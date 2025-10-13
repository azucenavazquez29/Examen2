<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Staff - agregar campos de autenticación
        Schema::table('staff', function (Blueprint $table) {
            $table->string('password')->change(); // Ya existe, solo cambiar si es necesario
            $table->enum('role', ['employee', 'admin'])->default('employee')->after('active');
            $table->timestamp('last_login')->nullable()->after('role');
        });

        // Customer - agregar contraseña
        Schema::table('customer', function (Blueprint $table) {
            $table->string('password')->nullable()->after('email');
            $table->timestamp('last_login')->nullable()->after('password');
        });
    }

    public function down(): void {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn(['role', 'last_login']);
        });

        Schema::table('customer', function (Blueprint $table) {
            $table->dropColumn(['password', 'last_login']);
        });
    }
};