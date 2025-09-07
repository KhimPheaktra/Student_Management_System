<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('DROP VIEW IF EXISTS class_view'); // Add this line

        DB::statement("
            CREATE VIEW class_view AS
            SELECT c.*,
                (
                    SELECT COUNT(ct.id)
                    FROM class_teachers ct
                    WHERE ct.class_id = c.id
                ) AS total_teacher
            FROM classes c;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS class_view'); // Corrected line
    }
};