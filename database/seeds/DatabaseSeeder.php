<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $db     = Config::get('database.connections.mysql.database');
        $user   = Config::get('database.connections.mysql.username');
        $pass   = Config::get('database.connections.mysql.password');

        $command = sprintf("mysql -u %s %s %s < database/fixtures/rota_slot_staff.sql", $user, $pass ? "-p $pass" : '', $db);
        exec($command);
    }
}
