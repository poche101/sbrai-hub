<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the existing index that covers tokenable_id before changing its type
        DB::statement('ALTER TABLE personal_access_tokens DROP INDEX personal_access_tokens_tokenable_type_tokenable_id_index');
        DB::statement('ALTER TABLE personal_access_tokens MODIFY tokenable_id VARCHAR(36) NOT NULL');
        DB::statement('ALTER TABLE personal_access_tokens ADD INDEX personal_access_tokens_tokenable_type_tokenable_id_index (tokenable_type, tokenable_id)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE personal_access_tokens DROP INDEX personal_access_tokens_tokenable_type_tokenable_id_index');
        DB::statement('ALTER TABLE personal_access_tokens MODIFY tokenable_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE personal_access_tokens ADD INDEX personal_access_tokens_tokenable_type_tokenable_id_index (tokenable_type, tokenable_id)');
    }
};
