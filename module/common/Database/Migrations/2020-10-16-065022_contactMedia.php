<?php namespace Modules\Common\Database\Migrations;

use CodeIgniter\Database\Migration;

class ContactMedia extends Migration
{

    public function up()
    {
        //

        /*
        * Setting
        */
        $this->forge->addField([
            'id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'contact_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'path' => ['type' => 'varchar', 'constraint' => 255],

        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('contact_id', 'contact', 'id', false, 'CASCADE');
        $this->forge->createTable('contact_media', true);

    }

    //--------------------------------------------------------------------

    public function down()
    {
        // drop constraints first to prevent errors
        if ($this->db->DBDriver != 'SQLite3') {
            // $this->forge->dropForeignKey('auth_tokens', 'auth_tokens_user_id_foreign');
        }

        $this->forge->dropTable('contact_media', true);
    }

}
