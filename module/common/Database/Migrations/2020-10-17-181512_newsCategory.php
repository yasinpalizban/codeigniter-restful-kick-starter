<?php namespace Modules\Common\Database\Migrations;

use CodeIgniter\Database\Migration;

class NewsCategory extends Migration
{

    public function up()
    {
        //

        /*
        * Setting
        */
        $this->forge->addField([
            'id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'varchar', 'constraint' => 255],
            'language' => ['type' => 'varchar', 'constraint' => 255],

        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('news_category', true);

    }

    //--------------------------------------------------------------------

    public function down()
    {
        // drop constraints first to prevent errors
        if ($this->db->DBDriver != 'SQLite3') {
            // $this->forge->dropForeignKey('auth_tokens', 'auth_tokens_user_id_foreign');
        }

        $this->forge->dropTable('news_category', true);
    }
}
