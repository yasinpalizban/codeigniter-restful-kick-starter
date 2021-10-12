<?php namespace Modules\Common\Database\Migrations;

use CodeIgniter\Database\Migration;

class NewsMedia extends Migration
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
            'image' => ['type' => 'varchar', 'constraint' => 255],
            'thumbnail' => ['type' => 'varchar', 'constraint' => 255],
            'video' => ['type' => 'varchar', 'constraint' => 255],

        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('post_id', 'news_post', 'id', false, 'CASCADE');
        $this->forge->createTable('news_media', true);

    }

    //--------------------------------------------------------------------

    public function down()
    {
        // drop constraints first to prevent errors
        if ($this->db->DBDriver != 'SQLite3') {
            // $this->forge->dropForeignKey('auth_tokens', 'auth_tokens_user_id_foreign');
        }

        $this->forge->dropTable('news_media', true);
    }

}
