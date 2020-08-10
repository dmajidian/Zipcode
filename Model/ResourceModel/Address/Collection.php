<?php
namespace Majidian\Zipcode\Model\ResourceModel\Address;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'zipcode_id';

    protected function _construct()
    {
        $this->_init('Majidian\Zipcode\Model\Address', 'Majidian\Zipcode\Model\ResourceModel\Address');
    }
}
