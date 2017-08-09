<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170801055022_order extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $table = $schema->createTable('orders');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('user_id', 'integer');
        $table->addColumn('name', 'text');        
        $table->addColumn('phone', 'text');
        $table->addColumn('address', 'integer');
        $table->addColumn('cost', 'integer');
        $table->addColumn('status', 'integer', ['default' => 0]);
        $table->addColumn('completed_at', 'datetime');
        $table->addColumn('date_created', 'datetime');
        $table->setPrimaryKey(['id']);
        $table->addOption('engine', 'InnoDB');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->dropTable('orders');
    }
}
