<?php

    /**
     * Shopware 4.0
     * Copyright © 2012 shopware AG
     *
     * According to our dual licensing model, this program can be used either
     * under the terms of the GNU LESSER GENERAL PUBLIC LICENSE, version 3,
     * or under a proprietary license.
     *
     * The texts of the GNU LESSER GENERAL PUBLIC LICENSE with an additional
     * permission and of our proprietary license can be found at and
     * in the LICENSE file you have received along with this program.
     *
     * This program is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
     * GNU Affero General Public License for more details.
     *
     * "Shopware" is a registered trademark of shopware AG.
     * The licensing of the program under the AGPLv3 does not imply a
     * trademark license. Therefore any rights, title and interest in
     * our trademarks remain entirely with us.
     *
     * @category   Shopware
     * @package    Shopware_Plugins
     * @subpackage Sofort
     * @author     PayIntelligent
     */
    /**
     * Abstract Payment Controller class dealing with all shared processes of the plugins payment means
     */
    abstract class Shopware_Plugins_Frontend_SofagPayment_Controller_Frontend_PaymentAbstract
        extends Shopware_Controllers_Frontend_Payment
    {
        protected $_loggingSource = "abstract";

        /**
         * First Action to be accessed during checkout
         */
        public function indexAction()
        {
            Shopware()->Plugins()->Controller()->ViewRenderer()->setNoRender();
            $helper = new Shopware_Plugins_Frontend_SofagPayment_Components_Helpers_Helper();
            //Backup Basket
            $user      = $this->getUser();
            $sessionId = $user[ "additional" ][ "user" ][ "sessionID" ];
            $helper->database()->saveBasket( $sessionId );
            //Create Order
            $paymentUniqueId                     = $this->createPaymentUniqueId();
            Shopware()->Session()->sofagUniqueId = $paymentUniqueId;
            $ordernumber                         = $this->saveOrder( $paymentUniqueId, $paymentUniqueId,
                $helper->option()->getStateTranslation( $this->getPaymentShortName(), "temporary" ) );
            //Restore Basket
            $helper->database()->restoreBasket( $sessionId );
            $logger = new Shopware_Plugins_Frontend_SofagPayment_Components_Services_Logger();
            $logger->logManually( $this->_loggingSource,
                "Saved order with ordernumber " . $ordernumber . " and temporary transaction id " . $paymentUniqueId );
            if ( $this->getPaymentShortName() === 'sofortideal' || $this->getPaymentShortName() === 'sofortbanking' ) {
                $this->redirect( array( "action" => "gateway", "forceSecure" => 1 ) );
            }
        }

        /**
         * Handles success in the transactions
         */
        public function successAction()
        {
            //Clear Basket
            $user      = $this->getUser();
            $sessionId = $user[ "additional" ][ "user" ][ "sessionID" ];
            $helper    = new Shopware_Plugins_Frontend_SofagPayment_Components_Helpers_Helper();
            $helper->database()->clearBasket( $sessionId );
            $this->redirect(
                array(
                     "controller"  => "checkout",
                     "action"      => "finish",
                     "forceSecure" => 1,
                     "sUniqueID"   => Shopware()->Session()->sofagUniqueId
                )
            );
        }

        /**
         * Handles errors in the transactions
         */
        public function errorAction()
        {
            $request    = $this->Request()->getParams();
            $translator = new Shopware_Plugins_Frontend_SofagPayment_Components_Services_Translator();
            $logger     = new Shopware_Plugins_Frontend_SofagPayment_Components_Services_Logger();
            $helper     = new Shopware_Plugins_Frontend_SofagPayment_Components_Helpers_Helper();
            if ( $request[ 'error_codes' ] == "6001" ) {
                Shopware()->Session()->pigmbhErrorMessage = $translator->getSnippetByNumber( "1202",
                    "Die Zeit zur Durchführung der Zahlung ist aus Sicherheitsgründen abgelaufen. " .
                    "Es wurde keine Transaktion durchgeführt. Bitte führen Sie die Zahlung erneut aus." );
            } else {
                Shopware()->Session()->pigmbhErrorMessage = $translator->getSnippetByNumber( "1201",
                    "Die gewählte Zahlart ist leider nicht möglich, oder wurde auf Kundenwunsch abgebrochen." .
                    " Bitte wählen Sie eine andere Zahlart." );
            }
            if ( isset( $request[ 'sofagError' ] ) ) {
                $logger->logManually( $this->_loggingSource, "An error occurred: " . var_export( $request, true ) );
            }
            //Use temporary transaction id if there is no transactionId
            if ( empty( $request[ 'transactionId' ] ) ) {
                $transactionId = Shopware()->Session()->sofagUniqueId;
            } else {
                $transactionId = $request[ 'transactionId' ];
            }
            //Condemn order if appropriate
            if ( $helper->option()->isRemovingFailedTransactions() ) {
                $ordernumber = $helper->database()->getOrdernumberByTransactionId( $transactionId );
                $logger->logManually( $this->_loggingSource, "Removing order $ordernumber due to an error" );
                $helper->database()->removeOrder( $ordernumber );
            } else {
                $logger->logManually( $this->_loggingSource, "Changing Orderstate to mark it as failed" );
                $order = Shopware()->Modules()->Order();
                $order->setPaymentStatus(
                    $helper->database()->getOrderByTransactionId( $transactionId ),
                    $this->convertLibState( "payment_canceled", "unknown" ),
                    false
                );
            }
            $this->redirect(
                array(
                     "controller"   => "account",
                     "action"       => "payment",
                     "sTarget"      => "checkout",
                     "errorMessage" => 1,
                     "forceSecure"  => 1
                )
            );
        }

        /**
         * Uses the argumented lib state and reason codes to determine the desired
         * shopware state in accordance with the config
         *
         * @param String $state
         * @param String $reason
         *
         * @return int Status
         */
        public abstract function convertLibState( $state, $reason );

        /**
         * Deals with timeouts during transactions
         *
         * @todo set correct translationname
         */
        public function timeoutAction()
        {
            $request                                  = $this->Request()->getParams();
            $helper                                   = new Shopware_Plugins_Frontend_SofagPayment_Components_Helpers_Helper();
            $translator                               = new Shopware_Plugins_Frontend_SofagPayment_Components_Services_Translator();
            $logger                                   = new Shopware_Plugins_Frontend_SofagPayment_Components_Services_Logger();
            Shopware()->Session()->pigmbhErrorMessage = $translator->getSnippetByNumber( "1202",
                "Die Zeit zur Durchführung der Zahlung ist aus Sicherheitsgründen abgelaufen. " .
                "Es wurde keine Transaktion durchgeführt. Bitte führen Sie die Zahlung erneut aus." );
            //Condemn order if appropriate
            if ( $helper->option()->isRemovingFailedTransactions() ) {
                $ordernumber = $helper->database()->getOrdernumberByTransactionId( $request[ 'transactionId' ] );
                $logger->logManually( $this->_loggingSource, "Removing order $ordernumber due to a timeout" );
                $helper->database()->removeOrder( $ordernumber );
            } else {
                $logger->logManually( $this->_loggingSource, "Changing Orderstate to mark it as failed" );
                $order = Shopware()->Modules()->Order();
                $order->setPaymentStatus(
                    $helper->database()->getOrderByTransactionId( $request[ 'transactionId' ] ),
                    $this->convertLibState( "payment_canceled", "unknown" ), false
                );
            }
            $this->redirect(
                array(
                    "controller"   => "account",
                    "action"       => "payment",
                    "sTarget"      => "checkout",
                    "errorMessage" => 1,
                    "forceSecure"  => 1
                )
            );
        }
    }