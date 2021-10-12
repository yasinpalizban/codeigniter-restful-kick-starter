<?php
namespace Modules\Payment\Libraries;
use CodeIgniter\HTTP\ResponseInterface;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PayPalPayment
{
    /**
     * index function
     * @method : GET
     */
    private $_api_context;
    private bool $hasError;
    private array $errors;

    public function __construct()
    {
        $this->hasError = false;
        $this->errors = array();
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
    /**
     * @return bool
     */

    public function isHasError(): bool
    {
        return $this->hasError;
    }
    public function paymentValidation()
    {
        $config = new  \Modules\Payment\Config\ModulePaymentConfig();
        $paypal_configuration = $config->payPal;
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_configuration['client_id'], $paypal_configuration['secret']));
        $this->_api_context->setConfig($paypal_configuration['settings']);
        $session = Session();
        $payment_id = $session->get('paypal_payment_id');

        $session->remove('paypal_payment_id');
        if (empty($this->request->getGet('PayerID')) || empty($this->request->getGet('token'))) {
            $session->set('error', 'Payment failed');
            //  return Redirect::route('paywithpaypal');
        }
        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($this->request->getGet('PayerID'));
        $result = $payment->execute($execution, $this->_api_context);

        if ($result->getState() == 'approved') {
            $session->set('success', 'Payment success !!');
            //   return Redirect::route('paywithpaypal');
        }

        $session->set('error', 'Payment failed !!');
        //  return Redirect::route('paywithpaypal');


    }

    public function paymentGate()
    {
        $config = new  \Modules\Payment\Config\ModulePaymentConfig();
        $paypal_configuration = $config->payPal;
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_configuration['client_id'], $paypal_configuration['secret']));
        $this->_api_context->setConfig($paypal_configuration['settings']);


        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item_1 = new Item();

        $item_1->setName('Product 1')
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($this->request->getGet('amount'));

        $item_list = new ItemList();
        $item_list->setItems(array($item_1));

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($this->request->getGet('amount'));

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Enter Your transaction description');

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(' url status')
            ->setCancelUrl('url cancel ');

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            if (\Config::get('app.debug')) {
                //  \Session::put('error', 'Connection timeout');
                //   return Redirect::route('paywithpaypal');
            } else {
                //  \Session::put('error', 'Some error occur, sorry for inconvenient');
                // return Redirect::route('paywithpaypal');
            }
        }

        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        $session = Session();
        $session->set('paypal_payment_id', $payment->getId());

        if (isset($redirect_url)) {
            //  return Redirect::away($redirect_url);
        }

        $session->set('error', 'Unknown error occurred');
        //   return Redirect::route('paywithpaypal');



    }

}