<?php namespace Modules\Common\Database\Migrations;

use CodeIgniter\Database\Migration;

class NewsSubCategory extends Migration
{

    public function up()
    {
        //

        /*
        * Setting
        */
        $this->forge->addField([
            'id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'category_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],

            'name' => ['type' => 'varchar', 'constraint' => 255],
            'language' => ['type' => 'varchar', 'constraint' => 255],

        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('category_id', 'news_category', 'id', false, 'CASCADE');
        $this->forge->createTable('news_sub_category', true);

    }

    //--------------------------------------------------------------------

    public function down()
    {
        // drop constraints first to prevent errors
        if ($this->db->DBDriver != 'SQLite3') {
            // $this->forge->dropForeignKey('auth_tokens', 'auth_tokens_user_id_foreign');
        }

        $this->forge->dropTable('news_sub_category', true);
    }
}
