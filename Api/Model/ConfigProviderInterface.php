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
namespace Labofgood\ElasticsearchCluster\Api\Model;

/**
 * Used to get module configurations.
 *
 * @interface ConfigProviderInterface
 * @api
 */
interface ConfigProviderInterface
{
    /**
     * Set storeId for getting correct store configurations.
     *
     * @param int $storeId
     * @return $this
     */
    public function setStoreId(int $storeId): ConfigProviderInterface;

    /**
     * Get cluster hosts.
     *
     * @return array<string, array{
     *       'scheme': string,
     *       'hostname': string,
     *       'port': int,
     *   }>
     */
    public function getClusterHosts(): array;
}
