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
var config = {
    config: {
        mixins: {
            'Magento_AdvancedSearch/js/testconnection': {
                'Labofgood_ElasticsearchCluster/js/testconnection-mixin': true
            }
        }
    }
};
