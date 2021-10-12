<?php namespace Modules\Payment\Libraries;
class ZarinPayment
{
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
        $Authority = $_GET['Authority'];
        //  $data = array("merchant_id" => "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx", "authority" => $Authority, "amount" => 1000);
        $data = array("merchant_id" => "d8802288-00bb-4739-acfd-265d3b7ea9be", "authority" => $Authority, "amount" => 1000);
        $jsonData = json_encode($data);
        $ch = curl_init('https://sandbox.zarinpal.com/pg/v4/payment/verify.json');
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v4');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ));

        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        $result = json_decode($result, true);
        if ($err) {
           // echo "cURL Error #:" . $err;
            $this->errors["cURL Error #:"] = $err;
        } else {
            if ($result['data']['code'] == 100) {
             //   echo 'Transation success. RefID:' . $result['data']['ref_id'];
            } else {
                $this->hasError = true;
                $this->errors['code'] = 'code: ' . $result['errors']['code'];
                $this->errors['message'] = 'message: ' . $result['errors']['message'];

            }
        }
    }


    public function paymentGate()
    {
//        $data = array("merchant_id" => "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
//            "amount" => 1000,
//            "callback_url" => "https://sandbox.zarinpal.com/pg/v4/payment/request.json",
//            "description" => "خرید تست",
//            "metadata" => ["email" => "info@email.com", "mobile" => "09121234567"],
//        );
        $data = array("merchant_id" => "d8802288-00bb-4739-acfd-265d3b7ea9be",
            "amount" => 1000,
            "callback_url" => "http://testerdemo.ir/home/test",
            "description" => "خرید تست",
            "metadata" => ["email" => "info@email.com", "mobile" => "09121234567"],
        );
        $jsonData = json_encode($data);
        $ch = curl_init('https://sandbox.zarinpal.com/pg/v4/payment/request.json');
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ));

        $result = curl_exec($ch);
        $err = curl_error($ch);
        $result = json_decode($result, true, JSON_PRETTY_PRINT);
        curl_close($ch);


        if ($err) {
            //  echo "cURL Error #:" . $err;
            $this->errors["cURL Error #:"] = $err;
        } else {
            if (empty($result['errors'])) {

                if ($result['data']['code'] == 100) {
                    header('Location: https://sandbox.zarinpal.com/pg/StartPay/' . $result['data']["authority"]);
                }
            } else {
                $this->hasError = true;
                $this->errors['code'] = 'code: ' . $result['errors']['code'];
                $this->errors['message'] = 'message: ' . $result['errors']['message'];
            }
        }
    }


}
