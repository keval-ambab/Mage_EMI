<?php
namespace Ambab\EmiCalculator\Model;

use Ambab\EmiCalculator\Api\Data\AllemiInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;

class Allemi extends AbstractModel implements AllemiInterface, IdentityInterface
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    const CACHE_TAG = 'ambab_emi';
    
    //Unique identifier for use within caching
    protected $_cacheTag = self::CACHE_TAG;
    
    protected function _construct()
    {
        $this->_init('Ambab\EmiCalculator\Model\ResourceModel\Allemi');
    }
    
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];
        return $values;
    }

    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

    public function getId()
    {
        return parent::getData(self::ID);
    }
	
	public function getBankName()
    {
        return $this->getData(self::BANKNAME);
    }
	
	public function getInterestRate()
    {
        return $this->getData(self::INTERESTRATE);
    }
	
    public function getDuration()
    {
        return $this->getData(self::DURATION);
    }

	public function getStatus()
    {
        return $this->getData(self::STATUS);
    }
	
	public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }
	
	public function setBankName($bank_name)
    {
        return $this->setData(self::BANKNAME, $bank_name);
    }
	
	public function setInterestRate($interest_rate)
    {
        return $this->setData(self::INTERESTRATE, $interest_rate);
    }
    
    public function setDuration($duration)
    {
        return $this->setData(self::DURATION, $duration);
    }
	
	public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }
}
?>
