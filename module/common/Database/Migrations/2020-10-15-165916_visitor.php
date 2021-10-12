<?php namespace Modules\Common\Database\Migrations;

use CodeIgniter\Database\Migration;

class Visitor extends Migration
{
    public function up()
    {
        //

        /*
        * Setting
        */
        $this->forge->addField([
            'id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'ip' => ['type' => 'varchar', 'constraint' => 255],
            'country' => ['type' => 'varchar', 'constraint' => 255],
            'city' => ['type' => 'varchar', 'constraint' => 255],
            'os' => ['type' => 'varchar', 'constraint' => 255],
            'lat' => ['type' => 'varchar', 'constraint' => 255],
            'lang' => ['type' => 'varchar', 'constraint' => 255],
            'created_at' => ['type' => 'datetime', 'null' => true],

        ]);

        $this->forge->addKey('id', true);

        $this->forge->createTable('visitor', true);

    }

    //--------------------------------------------------------------------

    public function down()
    {
        // drop constraints first to prevent errors
        if ($this->db->DBDriver != 'SQLite3') {
            // $this->forge->dropForeignKey('auth_tokens', 'auth_tokens_user_id_foreign');
        }

        $this->forge->dropTable('visitor', true);
    }
}
