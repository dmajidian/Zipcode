<?php
namespace Majidian\Zipcode\Api\Data;


use Magento\Framework\Api\SearchResultsInterface;
/**
 * @api
 */
interface AddressSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \Majidian\Zipcode\Api\Data\AddressInterface[]
     */
    public function getItems();
    /**
     * @param \Majidian\Zipcode\Api\Data\AddressInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
