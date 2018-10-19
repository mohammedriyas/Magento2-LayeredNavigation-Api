<?php
namespace Riyas\Filter\Model;

use Riyas\Filter\Api\FilterInterface;

class Filter implements FilterInterface {

    protected $_storeManager;
    protected $_brandsFactory;
    protected $_categoryHelper;
    private $logger;

    public function __construct(\Psr\Log\LoggerInterface $logger, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Catalog\Model\Layer\Resolver $layerResolver, \Magento\Catalog\Helper\Category $categoryHelper
    ) {
        $this->_categoryHelper = $categoryHelper;
        $this->layerResolver = $layerResolver->get();
        $this->_storeManager = $storeManager;
        $this->logger = $logger;
    }

    /**
     *
     * @api
     * @param int $categoryId
     * @return $this
     */
    public function getActiveFilters($categoryId) {
        $this->layerResolver->setCurrentCategory($categoryId);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $fill = $objectManager->create('Magento\Catalog\Model\Layer\Category\FilterableAttributeList');
        $filterList = new \Magento\Catalog\Model\Layer\FilterList($objectManager, $fill);
        $filterAttributes = $filterList->getFilters($this->layerResolver);
        $filterArray = array();
        $i = 0;
        foreach ($filterAttributes as $filter) {
            $availablefilter = (string) $filter->getName();
            $items = $filter->getItems();
            $filterValues = array();
            $j = 0;
            foreach ($items as $item) {
                $filterValues[$j]['display'] = strip_tags($item->getLabel());
                $filterValues[$j]['label'] = $item->getValue();
                $filterValues[$j]['count'] = $item->getCount();
                $j++;
            }

            if (!empty($filterValues)) {
                $filterArray['availablefilter'][$availablefilter] = $filterValues;
            }
            $i++;
        }
        return array($filterArray);
    }

}
