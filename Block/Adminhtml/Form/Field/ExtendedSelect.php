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

namespace Labofgood\ElasticsearchCluster\Block\Adminhtml\Form\Field;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;

/**
 * Class ExtendedSelect
 *
 * Extended select input allows set options from a source model via DI.
 */
class ExtendedSelect extends Select
{
    /**
     * StoreColumn constructor.
     *
     * @param OptionSourceInterface $optionSource
     * @param Context $context
     * @param array<mixed> $data
     */
    public function __construct(
        private readonly OptionSourceInterface $optionSource,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Set "name" for <select> an element
     *
     * @param string $value
     *
     * @return ExtendedSelect
     */
    public function setInputName(string $value): ExtendedSelect
    {
        return $this->setName($value);
    }

    /**
     * Set "id" for <select> an element
     *
     * @param string $value
     *
     * @return ExtendedSelect
     */
    public function setInputId(string $value): ExtendedSelect
    {
        return $this->setId($value);
    }

    /**
     * Render block HTML
     *
     * @return string
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    public function _toHtml(): string
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->optionSource->toOptionArray());
        }

        return parent::_toHtml();
    }
}
