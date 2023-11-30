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

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\BlockInterface;
use Magento\Framework\View\Element\Html\Select;
use Magento\Framework\View\Helper\SecureHtmlRenderer;

/**
 * Class FieldArray
 *
 * Render a field array.
 * Allow configuration of the columns and the renderer for each column in DI.
 */
class FieldArray extends AbstractFieldArray
{
    /**
     * @param array<string, array{
     *      label: string,
     *      size?: string,
     *      style?: string,
     *      title?: string,
     *      class?: string,
     *      extra_params?: string,
     *      renderer: BlockInterface,
     *      rendererOptions: array{
     *          name: string,
     *          type: string,
     *          data: array<string, mixed>
     *         }
     *    }> $columns
     * @param Context $context
     * @param array<string, BlockInterface> $renderers
     * @param array<string, mixed> $data
     * @param SecureHtmlRenderer|null $secureRenderer
     */
    public function __construct(
        private readonly array $columns,
        Context $context,
        private array $renderers = [],
        array $data = [],
        ?SecureHtmlRenderer $secureRenderer = null
    ) {
        parent::__construct($context, $data, $secureRenderer);
    }

    /**
     * Prepare to render.
     *
     * @return void
     * @throws LocalizedException
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _prepareToRender(): void
    {
        foreach ($this->columns as $name => $columnOptions) {
            if (array_key_exists('rendererOptions', $columnOptions)) {
                $columnOptions['renderer'] = $this->makeRenderer($columnOptions);
            }

            if (array_key_exists('label', $columnOptions)) {
                $columnOptions['label'] = __($columnOptions['label']);
            }

            $this->addColumn($name, $columnOptions);
        }

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Row')->render();
    }

    /**
     * Prepare an existing row data object.
     *
     * @param DataObject $row
     * @throws LocalizedException
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _prepareArrayRow(DataObject $row): void
    {
        $options = [];

        foreach ($this->columns as $name => $columnOptions) {
            $renderer = null;

            if (array_key_exists('rendererOptions', $columnOptions)) {
                $renderer = $this->makeRenderer($columnOptions);
            }

            if ($renderer instanceof Select) {
                $active = $row->getData($name);

                if ($active !== null) {
                    $options['option_' . $renderer->calcOptionHash($active)] = 'selected="selected"';
                }
            }
        }

        $row->setData('option_extra_attrs', $options);
    }

    /**
     * Get active column options.
     *
     * @param array{
     *     label: string,
     *     size?: string,
     *     style?: string,
     *     title?: string,
     *     class?: string,
     *     extra_params?: string,
     *     renderer: BlockInterface,
     *     rendererOptions: array{
     *         name: string,
     *         type: string,
     *         data: array<string, mixed>
     *        }
     *     } $columnOptions
     *
     * @return BlockInterface
     * @throws LocalizedException
     */
    private function makeRenderer(array $columnOptions): BlockInterface
    {
        $rendererOptions = $columnOptions['rendererOptions'];
        $rendererName = $rendererOptions['name'];

        if (array_key_exists($rendererName, $this->renderers)) {
            return $this->renderers[$rendererName];
        }

        $renderer = $this->getLayout()
            ->createBlock(
                $rendererOptions['type'],
                $rendererName,
                $rendererOptions['data']
            );

        if ($renderer instanceof Select) {
            $selectAttributes = ['class', 'title', 'extra_params'];
            foreach ($selectAttributes as $attr) {
                if (array_key_exists($attr, $columnOptions)) {
                    $renderer->setData($attr, $columnOptions[$attr]);
                }
            }
        }

        $this->renderers[$rendererName] = $renderer;

        return $this->renderers[$rendererName];
    }
}
