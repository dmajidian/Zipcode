<?php
namespace Majidian\Zipcode\Model\Resolver;

use Magento\Authorization\Model\UserContextInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use Magento\Framework\GraphQl\Query\Resolver\ValueFactory;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Majidian\Zipcode\Model\AddressFactory;
use Magento\Framework\Webapi\ServiceOutputProcessor;
use Magento\Framework\Api\ExtensibleDataObjectConverter;

class CheckZipcode implements ResolverInterface
{
    /**
     * @var ValueFactory
     */
    private $valueFactory;

    /**
     * @var AddressFactory
     */
    private $addressFactory;

    /**
     * @var ServiceOutputProcessor
     */
    private $serviceOutputProcessor;

    /**
     * @var ExtensibleDataObjectConverter
     */
    private $dataObjectConverter;

    /**
     * CheckZipcode constructor.
     * @param ValueFactory $valueFactory
     * @param AddressFactory $addressFactory
     * @param ServiceOutputProcessor $serviceOutputProcessor
     * @param ExtensibleDataObjectConverter $dataObjectConverter
     */
    public function __construct(
        ValueFactory $valueFactory,
        AddressFactory $addressFactory,
        ServiceOutputProcessor $serviceOutputProcessor,
        ExtensibleDataObjectConverter $dataObjectConverter
    ) {
        $this->valueFactory = $valueFactory;
        $this->addressFactory = $addressFactory;
        $this->serviceOutputProcessor = $serviceOutputProcessor;
        $this->dataObjectConverter = $dataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!isset($args['zipcode'])) {
            throw new GraphQlAuthorizationException(
                __(
                    'zipcode should be specified',
                    [\Magento\Checkout\Api\Data\ShippingInformationInterface::SHIPPING_ADDRESS]
                )
            );
        }
        try {
            $data = $this->getZipcodeData($args['zipcode']);
            $result = function () use ($data) {
                return !empty($data) ? $data : [];
            };
            return $this->valueFactory->create($result);
        } catch (NoSuchEntityException $exception) {
            throw new GraphQlNoSuchEntityException(__($exception->getMessage()));
        } catch (LocalizedException $exception) {
            throw new GraphQlNoSuchEntityException(__($exception->getMessage()));
        }
    }

    /**
     *
     * @param int $context
     * @return array
     * @throws NoSuchEntityException|LocalizedException
     */
    private function getZipcodeData($zipcode) : array
    {
        try {
            $zipcodes = $this->addressFactory->create()->getCollection()->addFieldToFilter('zipcode', array('eq' => $zipcode));

            $addressData = [];

            if($zipcodes) {
                $i=0;
                foreach ($zipcodes as $zipcodeAddress) {
                    $addressData[$i]['zipcode'] = $zipcodeAddress->getData('zipcode');
                    $addressData[$i]['city'] = $zipcodeAddress->getData('city');
                    $addressData[$i]['country_id'] = $zipcodeAddress->getData('country_id');
                    $addressData[$i]['region_id'] = $zipcodeAddress->getData('region_id');
                    $i++;
                }
            }

            /*$addressData[$i]['zipcode'] = $zipcode;
            $addressData[$i]['city'] = 'xxx';
            $addressData[$i]['country_id'] = 'something';
            $addressData[$i]['region_id'] = 'else';*/
            return isset($addressData[0]) ? $addressData[0] : [];

        } catch (NoSuchEntityException $e) {
            return [];
        } catch (LocalizedException $e) {
            throw new NoSuchEntityException(__($e->getMessage()));
        }
    }
}
