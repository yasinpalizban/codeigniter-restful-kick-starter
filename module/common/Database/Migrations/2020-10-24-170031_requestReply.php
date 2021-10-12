<?php namespace Modules\Common\Database\Migrations;

use CodeIgniter\Database\Migration;

class RequestReply extends Migration
{
    public function up()
    {
        //

        /*
        * Setting
        */
        $this->forge->addField([
            'id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'post_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'user_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'reply_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true,'default' => 0],
            'message' => ['type' => 'text', 'null' => true,],
            'created_at' => ['type' => 'datetime', 'null' => true],
            'updated_at' => ['type' => 'datetime', 'null' => true],
            'deleted_at' => ['type' => 'datetime', 'null' => true],

        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('post_id', 'request_post', 'id', false, 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', false, 'CASCADE');
        $this->forge->createTable('request_reply', true);

    }

    //--------------------------------------------------------------------

    public function down()
    {
        // drop constraints first to prevent errors
        if ($this->db->DBDriver != 'SQLite3') {
            // $this->forge->dropForeignKey('auth_tokens', 'auth_tokens_user_id_foreign');
        }

        $this->forge->dropTable('request_reply', true);
    }
}
