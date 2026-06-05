<?php use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use
Illuminate\Support\Facades\Schema; return new class extends Migration { public function up(): void {
Schema::table('users', function (Blueprint $table) {
$table->string('occupation')->nullable()->after('address');
$table->string('home_type')->nullable()->after('occupation'); }); } public function down(): void {
Schema::table('users', function (Blueprint $table) { $table->dropColumn(['occupation', 'home_type']); }); }
};