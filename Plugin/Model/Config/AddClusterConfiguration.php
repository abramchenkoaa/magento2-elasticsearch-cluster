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

namespace Labofgood\ElasticsearchCluster\Plugin\Model\Config;

use Labofgood\ElasticsearchCluster\Api\Model\ConfigProviderInterface;
use Magento\Elasticsearch\Model\Config;
use Magento\Framework\Search\EngineResolverInterface;

/**
 * Class AddClusterConfiguration
 *
 * Extend the Elasticsearch configuration to include cluster configuration.
 */
class AddClusterConfiguration
{
    /**
     * @param ConfigProviderInterface $configProvider
     * @param EngineResolverInterface $engineResolver
     */
    public function __construct(
        private readonly ConfigProviderInterface $configProvider,
        private readonly EngineResolverInterface $engineResolver,
    ) {
    }

    /**
     * @param Config $subject
     * @param array{
     *     'hostname': string,
     *     'port': int,
     *     'index': string,
     *     'enableAuth': bool,
     *     'username': string,
     *     'password': string,
     *     'timeout': int,
     *     'cluster_hosts'?: array<string, array{
     *           'scheme': string,
     *           'hostname': string,
     *           'port': int,
     *     }>
     * } $result
     * @param array{
     *      'hostname': string,
     *      'port': int,
     *      'index': string,
     *      'enableAuth': bool,
     *      'username': string,
     *      'password': string,
     *      'timeout': int,
     *      'cluster_hosts'?: array<string, array{
     *            'scheme': string,
     *            'hostname': string,
     *            'port': int,
     *      }>
     *  } $options
     *
     * @return array{
     *      'hostname': string,
     *      'port': int,
     *      'index': string,
     *      'enableAuth': bool,
     *      'username': string,
     *      'password': string,
     *      'timeout': int,
     *      'cluster_hosts'?: array<string, array{
     *          'scheme': string,
     *          'hostname': string,
     *          'port': int,
     *      }>
     *  }
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterPrepareClientOptions(
        Config $subject,
        array $result,
        array $options
    ): array {
        if ($this->engineResolver->getCurrentSearchEngine() !== 'elasticsearch7_cluster') {
            return $result;
        }

        if (array_key_exists('cluster_hosts', $options)) {
            $clusterHosts = $options['cluster_hosts'];
        } else {
            $clusterHosts = $this->configProvider->getClusterHosts();
        }

        $firstHost = $clusterHosts[array_key_first($clusterHosts)];
        $result['hostname'] = sprintf('%s://%s', $firstHost['scheme'], $firstHost['hostname']);
        $result['port'] = $firstHost['port'];
        $result['cluster_hosts'] = $clusterHosts;

        return $result;
    }
}
