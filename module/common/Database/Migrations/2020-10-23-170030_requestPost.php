<?php namespace Modules\Common\Database\Migrations;

use CodeIgniter\Database\Migration;

class RequestPost extends Migration
{
    public function up()
    {

        $this->forge->addField([
            'id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'category_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'title' => ['type' => 'varchar', 'constraint' => 255],
            'status' => ['type' => 'tinyint', 'constraint' => 1, 'null' => 0, 'default' => 0],
            'body' => ['type' => 'text', 'null' => true,],
            'created_at' => ['type' => 'datetime', 'null' => true],
            'updated_at' => ['type' => 'datetime', 'null' => true],
            'deleted_at' => ['type' => 'datetime', 'null' => true],

        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('category_id', 'request_category', 'id', false, 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', false, 'CASCADE');
        $this->forge->createTable('request_post', true);

    }

    //--------------------------------------------------------------------

    public function down()
    {
        // drop constraints first to prevent errors
        if ($this->db->DBDriver != 'SQLite3') {
            // $this->forge->dropForeignKey('auth_tokens', 'auth_tokens_user_id_foreign');
        }

        $this->forge->dropTable('request_post', true);
    }
}
