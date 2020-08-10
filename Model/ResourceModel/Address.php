<?php
namespace Majidian\Zipcode\Model\ResourceModel;

class Address extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('majidian_zipcode', 'zipcode_id');
    }
}
