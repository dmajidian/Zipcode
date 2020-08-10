<?php
namespace Majidian\Zipcode\Helper;

use \Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Store\Model\ScopeInterface;
use \Psr\Log\LoggerInterface;
use \Exception;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    /** @var bool  */
    const AUTOSAVE                    = true;
    /** @var string  */
    const ADDRESS_VERSION             = 'individual'; //individual|combined
    /** @var string  */
    const ORDER_STATUS_FIND           = 'pending';
    /** @var string  */
    const ORDER_STATUS_SET            = 'pending_address_validation';
    /** @var string  */
    const USA_MAGENTO_ID              = 'US';
    /** @var string  */
    const JSON_ENDPOINT_INTERNATIONAL = 'https://api.addressy.com/Cleansing/International/Batch/v1.00/json4.ws';
    /** @var string  */
    const JSON_ENDPOINT_DOMESTIC      = 'https://api.addressy.com/CleansePlus/Batch/Cleanse/v1.1/json4.ws';
    /**
     * Loqate API Settings - For More Info Visit https://support.loqate.com/documentation/options/
     */
    /** @var string  */
    const OPT_PROCESS = 'Verify';
    /** @var int  */
    const OPT_VERSION = 1;
    /** @var int  */
    const OPT_CERTIFY = 1;
    /** @var int  */
    const OPT_ENHANCE = 1;
    /** @var string  */
    const OPT_SERVER  = 'Latn';
    /**
     * International Batch Cleanse API Keys
     */
    /** @var string  */
    const INTL_API_MATCHES            = 'Matches';
    /** @var string  */
    const INTL_API_AVC                = 'AVC';
    /** @var string  */
    const INTL_API_AQI                = 'AQI';
    /** @var string  */
    const INTL_API_ADDRESS            = 'Address';
    /** @var string  */
    const INTL_API_ADDRESS1           = 'Address1';
    /** @var string  */
    const INTL_API_ADDRESS2           = 'Address2';
    /** @var string  */
    const INTL_API_ADDRESS3           = 'Address3';
    /** @var string  */
    const INTL_API_ADDRESS4           = 'Address4';
    /** @var string  */
    const INTL_API_ADDRESS5           = 'Address5';
    /** @var string  */
    const INTL_API_COUNTRY            = 'Country';
    /** @var string  */
    const INTL_API_COUNTRYNAME        = 'CountryName';
    /** @var string  */
    const INTL_API_ISO2               = 'ISO3166-2';
    /** @var string  */
    const INTL_API_ISO3               = 'ISO3166-3';
    /** @var string  */
    const INTL_API_ISON               = 'ISO3166-N';
    /** @var string  */
    const INTL_API_ADMINISTRATIVEAREA = 'AdministrativeArea';
    /** @var string  */
    const INTL_API_HYPHENCLASS        = 'HyphenClass';
    /** @var string  */
    const INTL_API_POSTALCODE         = 'PostalCode';
    /** @var string  */
    const INTL_API_ORDERID            = 'OrderId';
    /**
     * AQI Index - For More Info Visit https://support.loqate.com/documentation/reportcodes/address-quality-index/
     */
    /** @var array  */
    const AQI_ACCEPTED_LEVELS         = ['A', 'B'/*, 'C', 'D', 'E'*/];
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Config constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param LoggerInterface $logger
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        LoggerInterface $logger
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
    }

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->scopeConfig->getValue('general/addressvalidation_api/active', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->scopeConfig->getValue('general/addressvalidation_api/key', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return array
     */
    public function getAqi()
    {
        $aqi = $this->scopeConfig->getValue('general/addressvalidation_api/aqi', ScopeInterface::SCOPE_STORE);
        $aqi = explode(',', $aqi);

        if(count($aqi) < 1){
            $aqi = ['A'];
        }
        return $aqi;
    }

    /**
     * @return mixed
     */
    protected function getGeocode()
    {
        return $this->scopeConfig->getValue('general/addressvalidation_api/geocode', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return bool
     */
    public function isGeocode()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return self::JSON_ENDPOINT_INTERNATIONAL;
    }

    /**
     * @param $streetObject
     * @return string
     */
    public function getStreet($streetObject)
    {
        $street1 = $street2 = '';
        if(is_array($streetObject)){
            if(isset($streetObject[0])){
                $street1 = $streetObject[0];
            }
            if(isset($streetObject[1])){
                $street2 = $streetObject[1];
            }
        }

        return trim($street1 . ' ' . $street2);
    }
}
