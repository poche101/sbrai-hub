<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// AuthController::googleAuth() / facebookAuth() are routed in
// routes/api.php ('auth/google', 'auth/facebook') but neither method
// existed anywhere in the app before this change — social login was a
// guaranteed 500 (BadMethodCallException). These columns back the new
// implementations of those two methods.
return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->unique()->after('cac_number');
            $table->string('facebook_id')->nullable()->unique()->after('google_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['google_id', 'facebook_id']);
        });
    }
};
