<?php

namespace ModestoSolar\DanfeLink\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{
    protected StoreManagerInterface $storeManager;
    /**
     * @var UrlInterface|mixed
     */
    protected $url;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        UrlInterface $url = null
    ) {
        $this->storeManager = $storeManager;
        $this->url = $url ?: ObjectManager::getInstance()->get(UrlInterface::class);

        parent::__construct($context);
    }

    public function getConfigValue($field)
    {
        return $this->scopeConfig->getValue("sales/danfe_button/$field", ScopeInterface::SCOPE_STORE);
    }

    public function isEnable()
    {
        return $this->getConfigValue('active');
    }

    public function getDanfeApiUrl()
    {
        return $this->getConfigValue("danfe_url");
    }
}
