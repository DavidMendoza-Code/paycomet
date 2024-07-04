<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use SoapClient;
use SoapFault;

class PaycometXMLService
{
    private string $endpoint;
    private string $merchantCode;
    private string $jetid;
    private string $terminal;
    private string $password;

    public function __construct()
    {
        $this->setCredential(
            config('BankstoreXMLPaycomet.endpoint'),
            config('BankstoreXMLPaycomet.merchantCode'),
            config('BankstoreXMLPaycomet.jetid'),
            config('BankstoreXMLPaycomet.terminal'),
            config('BankstoreXMLPaycomet.password')
        );
    }

    public function setCredential(string $endpoint, string $merchantCode, string $jetid, string $terminal, string $password): void
    {
        $this->endpoint = $endpoint;
        $this->merchantCode = $merchantCode;
        $this->jetid = $jetid;
        $this->terminal = $terminal;
        $this->password = $password;
    }

    public function addUserToken(string $jettoken)
    {
        $signature = hash("sha512",
            $this->merchantCode .
            $jettoken .
            $this->jetid .
            $this->terminal .
            $this->password
        );

        $ip = request()->ip();

        try {
            $clientSOAP = new SoapClient($this->endpoint);
            $response = $clientSOAP->add_user_token(
                $this->merchantCode,
                $this->terminal,
                $jettoken,
                $this->jetid,
                $signature,
                $ip
            );
            return $response;
        } catch (SoapFault $e) {
            Log::error('SOAP Error: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function executePurchase(
        string $idpayuser,
        string $tokenpayuser,
        float $amount,
        string $transreference,
        string $currency
    ) {
        $signature = hash("sha512",
            $this->merchantCode .
            $idpayuser .
            $tokenpayuser .
            $this->terminal .
            $amount .
            $transreference .
            $this->password
        );

        $ip = request()->ip();

        try {
            $clientSOAP = new SoapClient($this->endpoint);

            $response = $clientSOAP->execute_purchase(
                $this->merchantCode,
                $this->terminal,
                $idpayuser,
                $tokenpayuser,
                $amount,
                $transreference,
                $currency,
                $signature,
                $ip
            );
            return $response;
        } catch (SoapFault $e) {
            Log::error('SOAP Error: ' . $e->getMessage());
            return $e->getMessage();
        }
    }
}
