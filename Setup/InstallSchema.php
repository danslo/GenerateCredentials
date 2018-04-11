<?php

namespace Danslo\GenerateCredentials\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $tableName = $setup->getTable('repo_credentials');
        $table = $setup->getConnection()
            ->newTable($tableName)
            ->addColumn(
                'credential_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'ID'
            )->addColumn(
                'username',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Username'
            )
            ->addColumn(
                'password',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Password'
            )
            ->addColumn(
                'order_id',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => true, 'unsigned' => true],
                'Order ID'
            )->addForeignKey(
                $setup->getFkName(
                    'repo_credentials',
                    'order_id',
                    'sales_order',
                    'entity_id'
                ),
                'order_id',
                $setup->getTable('sales_order'),
                'entity_id',
                Table::ACTION_SET_NULL
            );
        $setup->getConnection()->createTable($table);
        $setup->endSetup();
    }
}