<?php
namespace Majidian\Zipcode\Block\Adminhtml\Address\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    protected $countryFactory;
    protected $country;
    protected $regionFactory;
    /**
     * Form constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param \Majidian\Zipcode\Model\Status $options
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Directory\Model\Config\Source\Country $country,
        \Magento\Directory\Model\RegionFactory $regionFactory,
        array $data = []
    ) {
        $this->country = $country;
        $this->regionFactory = $regionFactory;
        parent::__construct($context, $registry, $formFactory, $data);
    }
    /**
     * @return \Magento\Backend\Block\Widget\Form\Generic
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('row_data');
        $form = $this->_formFactory->create(
            ['data' => [
                            'id' => 'edit_form',
                            'enctype' => 'multipart/form-data',
                            'action' => $this->getData('action'),
                            'method' => 'post'
                        ]
            ]
        );

        if ($model->getZipcodeId()) {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Edit Address'), 'class' => 'fieldset-wide']
            );
            $fieldset->addField('zipcode_id', 'hidden', ['name' => 'zipcode_id']);
        } else {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Add New Address'), 'class' => 'fieldset-wide']
            );
        }


        $countryList =$this->country->toOptionArray();
        $country = $fieldset->addField(
            'country_id',
            'select',
            [
                'name' => 'country_id',
                'label' => __('Country'),
                'title' => __('Country'),
                'values' => $countryList,
                'class' => 'required-entry',
                'required' => true
            ]
        );

        $fieldset->addField(
            'region_id',
            'select',
            [
                'name' => 'region_id',
                'label' => __('Region/State/Province'),
                'title' => __('Region'),
                'values' =>  ['--Please Select Country--'],
                'class' => 'required-entry',
                'required' => true
            ]
        );

        $country->setAfterElementHtml("
            <script type=\"text/javascript\">
                    require([
                    'jquery',
                    'mage/template',
                    'jquery/ui',
                    'mage/translate'
                ],
                function($, mageTemplate) {

                   $('#edit_form').on('change', '#country_id', function(event){
                        $.ajax({
                               url : '". $this->getUrl('address/lists/regionlist') . "country/' +  $('#country_id').val(),
                                type: 'get',
                                dataType: 'json',
                               showLoader:true,
                               success: function(data){
                                    $('#region_id').empty();
                                    $('#region_id').append(data.htmlconent);
                               }
                            });
                   })
                }

            );
            </script>");


        $fieldset->addField(
            'city',
            'text',
            [
                'name' => 'city',
                'label' => __('City'),
                'id' => 'city',
                'title' => __('City'),
                'class' => 'required-entry',
                'required' => true,
            ]
        );
        $fieldset->addField(
            'zipcode',
            'text',
            [
                'name' => 'zipcode',
                'label' => __('Zipcode'),
                'id' => 'zipcode',
                'title' => __('Zipcode'),
                'class' => 'required-entry',
                'required' => true,
            ]
        );

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
