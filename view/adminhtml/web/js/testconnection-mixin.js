/**
 * Copyright © 2023 Anton Abramchenko. All rights reserved.
 *
 * Redistribution and use permitted under the BSD-3-Clause license.
 * For full details, see COPYING.txt.
 *
 * @author    Anton Abramchenko <anton.abramchenko@labofgood.com>
 * @copyright 2023 Anton Abramchenko
 * @license   See COPYING.txt for license details.
 */
define([
    'jquery',
    'Magento_Ui/js/modal/alert',
], function ($, alert) {
    'use strict';

    return function (originalWidget) {
        $.widget('mage.testConnection', originalWidget, {
            /**
             * Method triggers an AJAX request to check search engine connection,
             * overriding the original method from
             * vendor/magento/module-advanced-search/view/adminhtml/web/js/testconnection.js
             * @private
             */
            _connect: function () {
                console.log('testConnection mixin');
                let result = this.options.failedText,
                    element =  $('#' + this.options.elementId),
                    self = this,
                    params = {},
                    msg = '',
                    fieldToCheck = this.options.fieldToCheck || 'success';

                element.removeClass('success').addClass('fail');
                params = this.fieldMappingToParams(this.options.fieldMapping);

                $.ajax({
                    url: this.options.url,
                    showLoader: true,
                    data: params,
                    headers: this.options.headers || {}
                }).done(function (response) {
                    if (response[fieldToCheck]) {
                        element.removeClass('fail').addClass('success');
                        result = self.options.successText;
                    } else {
                        msg = response.errorMessage;

                        if (msg) {
                            alert({
                                content: msg
                            });
                        }
                    }
                }).always(function () {
                    $('#' + self.options.elementId + '_result').text(result);
                });
            },

            /**
             * Convert field mapping to request params
             *
             * @param fieldMapping
             *
             * @returns {{}}
             */
            fieldMappingToParams: function (fieldMapping) {
                let params = {};
                $.each(JSON.parse(fieldMapping), function (key, el) {
                    let configOption = $('#' + el);
                    if (configOption.is('input, textarea, select')) {
                        params[key] = configOption.val();
                    } else if (configOption.is('table')) {
                        let pattern = /\[(_[\d_]+)\]\[([^\]]+)\]$/;
                        configOption.find('tr').each(function () {
                            let row = $(this);
                            row.find('input, textarea, select').each(function () {
                                let match = $(this).attr('name').match(pattern);
                                if (match) {
                                    if (!params[key]) {
                                        params[key] = {};
                                    }
                                    if (!params[key][match[1]]) {
                                        params[key][match[1]] = {};
                                    }
                                    params[key][match[1]][match[2]] = $(this).val();
                                }
                            });
                        });
                    }
                });

                return params;
            }
        });

        return $.mage.testConnection;
    };
});
