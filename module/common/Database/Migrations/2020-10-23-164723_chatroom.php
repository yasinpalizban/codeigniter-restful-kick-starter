<?php namespace Modules\Common\Database\Migrations;

use CodeIgniter\Database\Migration;

class Chatroom extends Migration
{
    public function up()
    {
        //

        /*
        * Setting
        */
        $this->forge->addField([
            'id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'group_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'message' => ['type' => 'text', 'null' => true,],
            'reply_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true,'default' => 0],
            'status' => ['type' => 'tinyint', 'constraint' => 1, 'null' => 0, 'default' => 0],
            'created_at' => ['type' => 'datetime', 'null' => true],
            'updated_at' => ['type' => 'datetime', 'null' => true],
            'deleted_at' => ['type' => 'datetime', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('group_id', 'auth_groups', 'id', false, false);
        $this->forge->addForeignKey('user_id', 'users', 'id', false, false);
        $this->forge->createTable('chat_room', true);

    }

    //--------------------------------------------------------------------

    public function down()
    {
        // drop constraints first to prevent errors
        if ($this->db->DBDriver != 'SQLite3') {
            // $this->forge->dropForeignKey('auth_tokens', 'auth_tokens_user_id_foreign');
        }

        $this->forge->dropTable('chat_room', true);
    }
}
