<?php
namespace Majidian\Zipcode\Plugin\Checkout;

use Magento\Checkout\Block\Checkout\LayoutProcessor;
use Magento\Checkout\Helper\Data;

class AddZipcodeValidationPlugin
{
    /**
     *
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $processor
     * @param array $jsLayout
     * @return array
     */
    public function afterProcess(LayoutProcessor $processor, $jsLayout)
    {
        $shippingConfiguration = &$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
        ['children']['shippingAddress']['children']['shipping-address-fieldset']['children'];
        $billingConfiguration = &$jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
        ['children']['payment']['children']['payments-list']['children'];

        if (isset($shippingConfiguration)) {
            $shippingConfiguration = $this->processAddress(
                $shippingConfiguration,
                'shippingAddress',
                [
                    'checkoutProvider',
                    'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.street',
                    'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.city',
                    'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.country_id'
                ]
            );
        }

        if (isset($billingConfiguration)) {
            foreach($billingConfiguration as $key => &$billingForm) {
                if (!strpos($key, '-form')) {
                    continue;
                }
                $billingForm['children']['form-fields']['children'] = $this->processAddress(
                    $billingForm['children']['form-fields']['children'],
                    $billingForm['dataScopePrefix'],
                    [
                        'checkoutProvider',
                        'checkout.steps.billing-step.payment.payments-list.' . $key . '.form-fields.street',
                        'checkout.steps.billing-step.payment.payments-list.' . $key . '.form-fields.city',
                        'checkout.steps.billing-step.payment.payments-list.' . $key . '.form-fields.country_id'
                    ]
                );
            }
        }

        return $jsLayout;
    }

    /**
     * @param $addressFieldset
     * @param $dataScope
     * @param $deps
     * @return array
     */
    private function processAddress($addressFieldset, $dataScope, $deps)
    {
        $addressFieldset['postcode'] = [
            'component' => 'Majidian_Zipcode/js/view/zipcode-validation',
            'config' => [
                'customScope' => $dataScope,
                'template' => 'ui/form/field',
                'prefer' => 'checkbox'
            ],
            'dataScope' => $dataScope . '.postcode',
            'deps' => $deps,
            'label' => __('Zipcode'),
            'provider' => 'checkoutProvider',
            'visible' => true,
            'initialValue' => false,
            'sortOrder' => 110,
            'valueMap' => [
                'true' => true,
                'false' => false
            ]
        ];

        if (isset($addressFieldset['street']['children'])) {
            foreach($addressFieldset['street']['children'] as $key => $street) {
                $street['tracks']['label'] = true;
                $street['additionalClasses'] = '';
                $addressFieldset['street']['children'][$key] = $street;
            }
        }

        return $addressFieldset;
    }
}
