<?php
/**
 * Copyright 2017 Lengow SAS
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category    Lengow
 * @package     Lengow_Connector
 * @subpackage  UI
 * @author      Team module <team-module@lengow.com>
 * @copyright   2017 Lengow SAS
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Lengow\Connector\Ui\Component\Listing\Column;


use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Asset\Repository;
use Magento\Directory\Model\CountryFactory;

class Country extends Column
{
    /**
     * @var \Magento\Framework\View\Asset\Repository Magento asset repository instance
     */
    protected $_assetRepo;

    /**
     * @var \Magento\Directory\Model\CountryFactory Magento country factory instance
     */
    protected $_countryFactory;

    /**
     * Constructor
     *
     * @param \Magento\Directory\Model\CountryFactory $countryFactory Magento country factory instance
     * @param \Magento\Framework\View\Asset\Repository $assetRepo Magento asset repository instance
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context Magento ui context instance
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory Magento ui factory instance
     * @param array $components component data
     * @param array $data additional params
     */
    public function __construct(
        CountryFactory $countryFactory,
        Repository $assetRepo,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->_assetRepo = $assetRepo;
        $this->_countryFactory = $countryFactory;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource row data source
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        $dataSource = parent::prepareDataSource($dataSource);
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (!is_null(['delivery_country_iso']) && strlen($item['delivery_country_iso']) === 2) {
                    $filename = $this->_assetRepo->getUrl('Lengow_Connector/images/flag')
                        . DIRECTORY_SEPARATOR . strtoupper($item['delivery_country_iso']) . '.png';
                    $country_name = $this->_countryFactory->create()
                        ->loadByCode($item['delivery_country_iso'])
                        ->getName();
                    $item['delivery_country_iso'] = '<a class="lengow_tooltip" href="#">
                    <img src="' . $filename . '" class="lengow_order_country" />
                    <span class="lengow_order_country">' . $country_name . '</span></a>';
                }
            }
        }
        return $dataSource;
    }
}