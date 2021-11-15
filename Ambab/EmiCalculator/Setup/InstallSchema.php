<?php
 
namespace Ambab\EmiCalculator\Setup;
 
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
 
class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $tableName = $installer->getTable('ambab_emi');
        //Check for the existence of the table
        if ($installer->getConnection()->isTableExists($tableName) != true) {
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'ID'
                )
                ->addColumn(
                    'bank_name',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Bank Name'
                )
                ->addColumn(
                    'interest_rate',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false],
                    'Interest Rate'
                )
                ->addColumn(
                    'duration',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false],
                    'Duration'
                )
                ->addColumn(
                    'status',
                    Table::TYPE_SMALLINT,
                    null,
                    ['nullable' => false, 'default' => '0'],
                    'Status'
                )->addIndex(
                    'ambab_emi', //table name
                    'bank_name',    // index name
                    [
                        'bank_name'   // filed or column name 
                    ],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT //type of index
                )
                //Set comment for ambab_emi table
                ->setComment('Ambab Emi Table')
                //Set option for ambab_emi table
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}
