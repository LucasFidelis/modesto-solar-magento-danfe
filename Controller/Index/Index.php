<?php

namespace ModestoSolar\DanfeLink\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Exception\LocalizedException;
use ModestoSolar\DanfeLink\Helper\Data;
use ModestoSolar\DanfeLink\Model\DanfeModel\DanfePdf;

class Index implements HttpGetActionInterface
{
    private Http $request;
    protected Data $helperData;
    private DanfePdf $danfePdf;

    public function __construct(
        Http $request,
        Data $helperData,
        DanfePdf $danfePdf
    ) {
        $this->request = $request;
        $this->helperData = $helperData;
        $this->danfePdf = $danfePdf;
    }

    /**
     * @throws \Zend_Pdf_Exception
     * @throws LocalizedException
     */
    public function execute()
    {
        $invoiceKeys = $this->request->getParam('key');
        $danfeKeys = explode("-", $invoiceKeys);

        header('Content-type: application/pdf');
        echo $this->danfePdf->getDanfePdf($danfeKeys);
    }
}
