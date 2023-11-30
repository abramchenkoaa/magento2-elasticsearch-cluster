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

namespace Labofgood\ElasticsearchCluster\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class HttpScheme
 *
 * Http scheme options source.
 */
class HttpScheme implements OptionSourceInterface
{
    /**
     * Return array of options as value-label pairs.
     *
     * @return array<int, array{'label': string, 'value': string}>
     */
    public function toOptionArray(): array
    {
        return [
            ['label' => 'http', 'value' => 'http'],
            ['label' => 'https', 'value' => 'https'],
        ];
    }
}
