/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */
define([
    'jquery',
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/abstract',
    'mage/url'
], function ($, _, registry, Abstract, urlBuilder) {
    'use strict';

    return Abstract.extend({
        defaults: {
            modules: {
                region: '${ $.parentName }.region_id',
                city: '${ $.parentName }.city',
                country: '${ $.parentName }.country_id'
            }
        },
        validate: function () {
            this._super();
            this.checkZipcode();
        },
        checkZipcode: function () {
            var zipcode = this.value();
            var self = this;
            var body = $('body').loader();
            //body.loader('show');

            var url = urlBuilder.build("graphql");
            $.ajax({
                method: "POST",
                url: url,
                contentType: "application/json",
                type: 'GET',
                data: JSON.stringify({
                    query: `{checkzipcode(zipcode: "` + zipcode + `") {
                zipcode
                country_id
                region_id
                city
                }
              }`
                }),
                success: function(res) {
                    body.loader('hide');
                    var strRes = JSON.stringify(res);
                    var result = JSON.parse(strRes);
                    if (result.data.checkzipcode.zipcode != null) {
                        self.updateCity(result.data.checkzipcode.city);
                        self.updateRegion(result.data.checkzipcode.region_id);
                        self.updateCountry(result.data.checkzipcode.country_id);
                    } else {
                        //failed.
                    }

                }
            });
        },
        updateCity: function (city) {
            this.city().value(city);
        },
        updateRegion: function (region_id) {
            this.region().value(region_id);
        },
        updateCountry: function (country_id) {
            this.country().value(country_id);
        }
    });
});
