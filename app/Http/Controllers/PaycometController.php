<?php

namespace App\Http\Controllers;

use App\Services\PaycometService;
use App\Services\PaycometXMLService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaycometController extends Controller
{
    private PaycometService $paycometService;
    private PaycometXMLService $paycometXMLService;

    public function __construct(PaycometService $paycometService, PaycometXMLService $paycometXMLService)
    {
        $this->paycometService = $paycometService;
        $this->paycometXMLService = $paycometXMLService;
    }

    public function index()
    {
        return view('paycomet-payment');
    }

    public function handleCallback(Request $request)
    {
        try {
            $jetToken = $request->input('paytpvToken');
            $cardHolderName = $request->input('username');
            $amount = $request->input('amount');
            $productDescription = '';
            $order = 'PAY' . rand(100000, 999999);

            if (empty($jetToken) || empty($cardHolderName) || empty($amount)) {
                return response()->json(['error' => 'Datos de entrada incompletos'], 400);
            }

            $userInfo = $this->paycometService->postAddUser($jetToken, $order, $productDescription, $cardHolderName);
            $idUser = $userInfo['idUser'] ?? null;
            $tokenUser = $userInfo['tokenUser'] ?? null;

            if (!$idUser || !$tokenUser) {
                return response()->json(['error' => 'Error al agregar usuario'], 400);
            }

            $url_response = route('payment.result');
            $paymentInfo = $this->paycometService->postExecutePayment($order, $amount, $idUser, $tokenUser, $url_response);

            if ($paymentInfo['errorCode'] !== 0) {
                return response()->json(['error' => $paymentInfo['errorCode']], 400);
            }

            if (!isset($paymentInfo['challengeUrl'])) {
                return response()->json(['error' => 'URL de redireccionamiento inválida'], 400);
            }

            return redirect()->away($paymentInfo['challengeUrl']);
        } catch (\Exception $e) {
            Log::error('Error en handleCallback: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }
    public function handleCallbackSoap(Request $request)
    {
        try {
            $jetToken = $request->input('paytpvToken');
            $amount = $request->input('amount');
            $currency = 'EUR';
            $transreference = 'PAY' . rand(100000, 999999);

            $userInfo = $this->paycometXMLService->addUserToken($jetToken);

            $idpayuser = $userInfo['DS_IDUSER'] ?? null;
            $tokenpayuser = $userInfo['DS_TOKEN_USER'] ?? null;

            if (!$idpayuser || !$tokenpayuser) {
                return response()->json(['error' => 'Error al agregar usuario'], 400);
            }


            $paymentInfo = $this->paycometXMLService->executePurchase(
                $idpayuser,
                $tokenpayuser,
                $amount,
                $transreference,
                $currency
            );

            if ($paymentInfo['DS_ERROR_ID'] !== 0) {
                return response()->json(['error' => $paymentInfo['DS_ERROR_ID']], 400);
            }

            if (!isset($paymentInfo['DS_CHALLENGE_URL'])) {
                return response()->json(['error' => 'URL de redireccionamiento inválida'], 400);
            }

            return redirect()->away($paymentInfo['DS_CHALLENGE_URL']);
        } catch (\Exception $e) {
            Log::error('Error en handleCallbackSoap: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }
    public function authenticationResult(Request $request)
    {
        try {
            $result = $request->input('result');

            if (!in_array($result, ['okAuth', 'koAuth'])) {
                return response()->json(['error' => 'Resultado de autenticación inválido'], 400);
            }

            return view('paycomet-payment-result', ['result' => ($result === 'okAuth') ? 'ok' : 'ko']);

        } catch (\Exception $e) {
            Log::error('Error en authenticationResult: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }
}
