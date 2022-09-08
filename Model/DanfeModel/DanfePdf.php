<?php

namespace ModestoSolar\DanfeLink\Model\DanfeModel;

use Magento\Framework\Exception\LocalizedException;
use ModestoSolar\DanfeLink\Helper\Data;
use Zend_Pdf;
use Zend_Pdf_Resource_Extractor;

class DanfePdf
{

    protected Data $helperData;
    public Zend_Pdf $pdf;
    protected Zend_Pdf_Resource_Extractor $pdfPage;

    public function __construct(
        Data $helperData,
        Zend_Pdf $pdf,
        Zend_Pdf_Resource_Extractor $page
    ) {
        $this->helperData = $helperData;
        $this->pdf = $pdf;
        $this->pdfPage = $page;
    }

    /**
     * @throws \Zend_Pdf_Exception
     * @throws LocalizedException
     */
    public function getDanfePdf($danfeKeys)
    {
        if(!$this->helperData->isEnable()){
            return;
        }

        if (!$this->pdf instanceof \Zend_Pdf || !$this->pdfPage instanceof \Zend_Pdf_Resource_Extractor) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Please define the PDF object before using.'));
        }

        if ($danfeKeys && count($danfeKeys) > 1) {
            foreach ($danfeKeys as $danfeKey) {
                $pdfFile = $this->helperData->getDanfeApiUrl() . $danfeKey;
                $fileContent = file_get_contents($pdfFile);
                $basePdf = $this->pdf::parse($fileContent, 1);
                $this->pdf->pages[] = $this->pdfPage->clonePage($basePdf->pages[0]);
            }
            return $this->pdf->render();
        } else {
            return file_get_contents($this->helperData->getDanfeApiUrl() . $danfeKeys[0]);
        }
    }
}
