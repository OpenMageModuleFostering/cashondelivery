<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Phoenix
 * @package    Phoenix_CashOnDelivery
 * @copyright  Copyright (c) 2010 Phoenix Medien GmbH & Co. KG (http://www.phoenix-medien.de)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Phoenix_CashOnDelivery_Model_Invoice_Tax extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Invoice $invoice)
    {
        $codTax = 0;
        $baseCodTax = 0;

        $includeCodTax = true;
        /**
         * Check Cod amount in previus invoices
         */
        foreach ($invoice->getOrder()->getInvoiceCollection() as $previusInvoice) {
            if ($previusInvoice->getCodFee() && !$previusInvoice->isCanceled()) {
                $includeCodTax = false;
            }
        }

        if ($includeCodTax) {
            $codTax += $invoice->getOrder()->getCodTaxAmount();
            $baseCodTax += $invoice->getOrder()->getBaseCodTaxAmount();
            $invoice->setCodTaxAmount($invoice->getOrder()->getCodTaxAmount());
            $invoice->setBaseCodTaxAmount($invoice->getOrder()->getBaseCodTaxAmount());
            $invoice->getOrder()->setCodTaxAmountInvoiced($codTax);
            $invoice->getOrder()->setBaseCodTaxAmountInvoice($baseCodTax);
        }

        $invoice->setTaxAmount($invoice->getTaxAmount() + $codTax);
        $invoice->setBaseTaxAmount($invoice->getBaseTaxAmount() + $baseCodTax);

        $invoice->setGrandTotal($invoice->getGrandTotal() + $codTax);
        $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseCodTax);

        return $this;
    }
}