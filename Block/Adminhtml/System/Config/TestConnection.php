<?php
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
declare(strict_types=1);

namespace Labofgood\ElasticsearchCluster\Block\Adminhtml\System\Config;

use Magento\AdvancedSearch\Block\Adminhtml\System\Config\TestConnection as MagentoTestConnection;

/**
 * Class TestConnection
 *
 * Block used to test Elasticsearch 7.x cluster connection from the admin panel.
 */
class TestConnection extends MagentoTestConnection
{
    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _getFieldMapping(): array
    {
        $fields = [
            'engine' => 'catalog_search_engine',
            'cluster_hosts' => 'catalog_search_elasticsearch7_cluster_hosts',
            'index' => 'catalog_search_elasticsearch7_cluster_index_prefix',
            'enableAuth' => 'catalog_search_elasticsearch7_cluster_enable_auth',
            'username' => 'catalog_search_elasticsearch7_cluster_username',
            'password' => 'catalog_search_elasticsearch7_cluster_password',
            'timeout' => 'catalog_search_elasticsearch7_cluster_server_timeout',
        ];

        return array_merge(parent::_getFieldMapping(), $fields);
    }
}
