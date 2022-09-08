<?php

declare(strict_types=1);

namespace ModestoSolar\DanfeLink\Block\Order;

use Magento\Framework\View\Element\Template\Context as TemplateContext;
use Magento\Framework\Registry;
use Magento\Sales\Block\Items\AbstractItems;
use ModestoSolar\DanfeLink\Helper\Data;

class DanfeButton extends AbstractItems
{
    const ACCESSKEYPATTERN = '/^\d{44}$/';
    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry = null;

    /**
     * Constructor
     * @param TemplateContext $context
     * @param Registry $registry
     * @param Data $helperData
     * @param array $data
     */
    public function __construct(
        TemplateContext $context,
        Registry $registry,
        Data $helperData,
        array $data = []
    ) {
        $this->helperData = $helperData;
        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    public function IsEnabled()
    {
        return $this->helperData->isEnable();
    }

    public function getOrder()
    {
        return $this->coreRegistry->registry('current_order');
    }

    public function getAllDanfeKeys(): array
    {
        $order = $this->getOrder();
        $danfeKeys = [];
        foreach ($order->getInvoiceCollection() as $_invoice) {
            foreach ($_invoice->getCommentsCollection() as $_comment) {
                if (preg_match(self::ACCESSKEYPATTERN, $_comment->getComment())) {
                    $danfeKeys[] = $_comment->getComment();
                }
            }
        }
        return $danfeKeys;
    }

    /**
     * @return string
     */
    public function getDanfeLink(): string
    {
        if ($this->getOrder()) {
            $danfeKeys = implode("-", $this->getAllDanfeKeys());
            $queryParams = ['key' => $danfeKeys];
            return $this->getUrl('danfe', ['_query' => $queryParams]);
        }
    }
}
