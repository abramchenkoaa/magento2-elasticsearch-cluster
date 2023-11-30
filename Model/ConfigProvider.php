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

namespace Labofgood\ElasticsearchCluster\Model;

use Labofgood\ElasticsearchCluster\Api\Model\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class ConfigProvider
 *
 * Module configurations provider.
 */
class ConfigProvider implements ConfigProviderInterface
{
    /**
     * Mapping configuration xml paths.
     */
    private const XML_PATH_ES_CLUSTER_HOSTS = 'catalog/search/elasticsearch7_cluster_hosts';

    /**
     * @var int|null
     */
    private ?int $storeId = null;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        private readonly ScopeConfigInterface  $scopeConfig,
        private readonly StoreManagerInterface $storeManager,
        private readonly SerializerInterface   $serializer
    ) {
    }

    /**
     * Get current store id.
     *
     * @return int
     * @throws NoSuchEntityException
     */
    private function getStoreId(): int
    {
        if ($this->storeId === null) {
            $this->setStoreId(
                (int) $this->storeManager
                        ->getStore()
                        ->getId()
            );
        }

        return $this->storeId;
    }

    /**
     * Set storeId for getting correct store configurations.
     *
     * @param int $storeId
     * @return $this
     */
    public function setStoreId(int $storeId): self
    {
        $this->storeId = $storeId;

        return $this;
    }

    /**
     * Get cluster hosts.
     *
     * @return array<string, array{
     *       'scheme': string,
     *       'hostname': string,
     *       'port': int,
     *   }>
     * @throws NoSuchEntityException
     */
    public function getClusterHosts(): array
    {
        $items = $this->scopeConfig->getValue(
            self::XML_PATH_ES_CLUSTER_HOSTS,
            ScopeInterface::SCOPE_STORE,
            $this->getStoreId()
        );

        $items = $this->serializer->unserialize($items);

        return is_array($items)
            ? $items
            : $this->getDefaultsHosts();
    }

    /**
     * Get default hosts for ES cluster
     *
     * @return array<string, array{
     *      'scheme': string,
     *      'hostname': string,
     *      'port': int,
     *  }>
     */
    private function getDefaultsHosts(): array
    {
        return [
            [
                'scheme' => 'http',
                'hostname' => 'localhost',
                'port' => 9200,
            ]
        ];
    }
}
