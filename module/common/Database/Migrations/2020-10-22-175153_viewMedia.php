<?php namespace Modules\Common\Database\Migrations;

use CodeIgniter\Database\Migration;

class ViewMedia extends Migration
{

    public function up()
    {
        //

        /*
        * Setting
        */
        $this->forge->addField([
            'id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'view_option_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'path' => ['type' => 'varchar', 'constraint' => 255],

        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('view_option_id', 'views_option', 'id', false, 'CASCADE');
        $this->forge->createTable('view_media', true);

    }

    //--------------------------------------------------------------------

    public function down()
    {
        // drop constraints first to prevent errors
        if ($this->db->DBDriver != 'SQLite3') {
            // $this->forge->dropForeignKey('auth_tokens', 'auth_tokens_user_id_foreign');
        }

        $this->forge->dropTable('view_media', true);
    }

}
