<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170801055016_product extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $table = $schema->createTable('products');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);        
        $table->addColumn('name', 'text', ['notnull' => true]);
        $table->addColumn('alias', 'text');
        $table->addColumn('price', 'integer', ['notnull' => true]);
        $table->addColumn('intro', 'text');
        $table->addColumn('image', 'string');
        $table->addColumn('popular_level', 'integer');
        $table->addColumn('description', 'text');
        $table->addColumn('status', 'integer');
        $table->addColumn('rate_avg', 'float');
        $table->addColumn('rate_count', 'integer');
        $table->addColumn('sale', 'integer');
        $table->addColumn('category_id', 'integer', ['notnull' => true]);
        $table->addColumn('store_id', 'integer', ['notnull' => true]);
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
        $schema->dropTable('products');
    }
}
