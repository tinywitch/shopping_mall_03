<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170801054639_category extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $table = $schema->createTable('categories');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);        
        $table->addColumn('name', 'text', ['notnull' => true]);
        $table->addColumn('alias', 'text');
        $table->addColumn('description', 'text');
        $table->addColumn('parent_id', 'integer');
        $table->addColumn('date_created', 'datetime', ['notnull' => true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine', 'InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->dropTable('categories');
    }
}
