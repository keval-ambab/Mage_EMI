<?php

namespace Ambab\EmiCalculator\Model\Allemi\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{
    protected $allEmi;

    public function __construct(\Ambab\EmiCalculator\Model\Allemi $allEmi)
    {
        $this->allEmi = $allEmi;
    }

    public function toOptionArray()
    {
        $availableOptions = $this->allEmi->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
?>
