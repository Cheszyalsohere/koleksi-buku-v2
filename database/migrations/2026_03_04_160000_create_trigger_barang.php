<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER trg_generate_id_barang
            BEFORE INSERT ON barangs
            FOR EACH ROW
            BEGIN
                DECLARE next_id INT;
                SELECT IFNULL(MAX(CAST(SUBSTRING(id_barang, 4) AS UNSIGNED)), 0) + 1
                INTO next_id
                FROM barangs;
                SET NEW.id_barang = CONCAT("BRG", LPAD(next_id, 3, "0"));
            END
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_generate_id_barang');
    }
};
