<?php
namespace Majidian\Zipcode\Api\Data;


use Magento\Framework\Api\SearchResultsInterface;
/**
 * @api
 */
interface AddressSearchResultInterface extends SearchResultsInterface
{
    /**
     * Get items
     * @return \Majidian\Zipcode\Api\Data\AddressInterface[]
     */
    public function getItems();
    /**
     * Set items
     * @param \Majidian\Zipcode\Api\Data\AddressInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
