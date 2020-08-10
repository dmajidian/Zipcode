<?php
namespace Majidian\Zipcode\Model;

use Majidian\Zipcode\Model\ResourceModel\Address\CollectionFactory;

class ZipcodeDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $zipcodeCollectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $zipcodeCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $zipcodeCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        return [];
    }
}
