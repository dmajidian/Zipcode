/**
 * @api
 */
define([
    'jquery',
    'mage/storage',
    'mage/url'
], function ($, storage, urlBuilder) {
    'use strict';

    return function (zipcode, deferred) {
        deferred = deferred || $.Deferred();
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
                return res;
            }
        });

        return false;
        /*return storage.get(
            'graphql' ,
            query
        ).done(function (response) {
            deferred.resolve();
        }).error(function (response) {
            deferred.reject();
        }).always(function () {
        });*/
    };
});
