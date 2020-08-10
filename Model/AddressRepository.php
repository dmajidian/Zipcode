<?php


namespace Majidian\Zipcode\Model;


use Mageprince\Test\Api\Data\TestInterface;

class AddressRepository implements \Majidian\Zipcode\Api\AddressInterface
{
    /**
     * @var \Mageprince\Test\Model\ResourceModel\Test\CollectionFactory
     */
    protected $testCollectionFactory;

    /**
     * @var \Mageprince\Test\Api\Data\TestSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;


    public function __construct(
        \Majidian\Zipcode\Model\ResourceModel\Address\CollectionFactory $testCollectionFactory,
        \Majidian\Zipcode\Api\Data\AddressSearchResultInterfaceFactory $searchResultsFactory
    ) {
        $this->testCollectionFactory = $testCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * @inheritdoc
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $collection = $this->testCollectionFactory->create();
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        $sortOrdersData = $searchCriteria->getSortOrders();
        if ($sortOrdersData) {
            foreach ($sortOrdersData as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }

        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $searchResults->setItems($collection->getData());
        return $searchResults;
    }
}
