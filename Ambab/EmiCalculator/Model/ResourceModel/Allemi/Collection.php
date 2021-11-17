<?php
namespace Ambab\EmiCalculator\Model\ResourceModel\Allemi;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';
    
    /**
     * route_controller_Collection
     */
    protected $_eventPrefix = 'emical_allemi_collection';
    
    /**
     * controller_Collection
     */
    protected $_eventObject = 'allemi_collection';
    
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init('Ambab\EmiCalculator\Model\Allemi', 'Ambab\EmiCalculator\Model\ResourceModel\Allemi');
    }
}
?>
