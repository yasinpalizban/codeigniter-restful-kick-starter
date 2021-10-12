<?php namespace Modules\Common\Database\Migrations;

use CodeIgniter\Database\Migration;

class Advertisement extends Migration
{
//
    public function up()
    {

        /*
        * Setting
        */
        $this->forge->addField([
            'id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'varchar', 'constraint' => 255],
            'link' =>  ['type' => 'varchar', 'constraint' => 255],
            'status' => ['type' => 'tinyint', 'constraint' => 1, 'null' => 0, 'default' => 1],
            'created_at' => ['type' => 'datetime', 'null' => true],
            'updated_at' => ['type' => 'datetime', 'null' => true],
            'deleted_at' => ['type' => 'datetime', 'null' => true],

        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('advertisement', true);

    }

//--------------------------------------------------------------------

    public function down()
    {
        // drop constraints first to prevent errors
        if ($this->db->DBDriver != 'SQLite3') {
            // $this->forge->dropForeignKey('auth_tokens', 'auth_tokens_user_id_foreign');
        }

        $this->forge->dropTable('advertisement', true);
    }
}
