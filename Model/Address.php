<?php
namespace Majidian\Zipcode\Model;

class Address extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Majidian\Zipcode\Model\ResourceModel\Address');
    }
}
