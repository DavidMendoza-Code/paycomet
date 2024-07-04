<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;

class PaycometService
{
    private $gatewayURL;

    private $apiToken;

    private $terminal;


    public function __construct()
    {
        $this->setCredential(
            config('apiPaycomet.gatewayURL'),
            config('apiPaycomet.api-token'),
            config('apiPaycomet.terminal'),
        );
    }

    public function setCredential(string $gatewayURL, string $apiToken, string $terminal): void
    {
        $this->gatewayURL = $gatewayURL;
        $this->apiToken = $apiToken;
        $this->terminal = $terminal;
    }


    public function postAddUser(string $jetToken, string $order, string $productDescription, string $cardHolderName): array
    {

        $data = [
            'terminal' => $this->terminal,
            'jetToken' => $jetToken,
            'order' => $order,
            'productDescription' => $productDescription,
            'language' => 'es',
            'notify' => 1,
            'cardHolderName' => $cardHolderName,
        ];
        $encodedData = json_encode($data, true);

        return $this->execCurlPost($encodedData, $this->gatewayURL . "/v1/cards");
    }


    public function postExecutePayment(string $order, float $amount, string $idUser, string $tokenUser, string $urlResponse): array
    {

        $data = [
            'language' => "es",
            'payment' => [
                'order' => $order,
                'amount' => $amount,
                'currency' => 'EUR',
                'originalIp' => '127.0.0.1',
                'methodId' => 1,
                'terminal' => $this->terminal,
                'secure' => 1,
                'idUser' => $idUser,
                'tokenUser' => $tokenUser,
                'productDescription' => 'Random product',
                'notifyDirectPayment' => 1,
                'urlOk' => $urlResponse.'/?result=okAuth&o='.$order,
                'urlKo' => $urlResponse.'/?result=koAuth&o='.$order,

                ]

        ];
        $encodedData = json_encode($data, true);

        return $this->execCurlPost($encodedData, $this->gatewayURL . "/v1/payments");
    }

    private function execCurlPost(string $data, string $url): array
    {
        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json',
                'PAYCOMET-API-TOKEN: ' . $this->apiToken,
            ]);

            $response = curl_exec($ch);

            if ($response === false) {
                throw new Exception('Curl error: ' . curl_error($ch));
            }

            curl_close($ch);

            Log::info('Paycomet Response', ['response' => $response]);

            $finalResponse = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('JSON decode error: ' . json_last_error_msg());
            }

            return $finalResponse;
        } catch (Exception $e) {
            Log::error('Paycomet API Request Failed', ['exception' => $e]);
            return ['error' => $e->getMessage()];
        }
    }


}
