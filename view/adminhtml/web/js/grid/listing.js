define([
    'Magento_Ui/js/grid/listing'
], function (Collection) {
    'use strict';

    return Collection.extend({
        defaults: {
            template: 'Majidian_Zipcode/ui/grid/listing'
        },
        getRowClass: function (row) {
            return '';
        }
    });
});
