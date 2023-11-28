<?php

namespace Tm\OrangeMoneySDK\Http\OneStepPay;

use Tm\OrangeMoneySDK\Domain\Entity\Enum\PaiementResponseStatus;
use Tm\OrangeMoneySDK\Domain\Entity\OmResponseError;
use Tm\OrangeMoneySDK\Domain\Entity\Transaction;
use Tm\OrangeMoneySDK\Http\io\HttpClientInterface;
use Tm\OrangeMoneySDK\Http\io\HttpException;
use Tm\OrangeMoneySDK\Http\io\HttpResponseCallbackInterface;
use Tm\OrangeMoneySDK\Http\Token\Token;

class OneStepPayService
{
    private const SUFFIX = '/api/eWallet/v1/payments/onestep';

    private HttpClientInterface $client;

    private Token $token;

    public function __construct(HttpClientInterface $client, Token $token)
    {
        $this->client = $client;
        $this->token = $token;
    }

    public function execute(HttpResponseCallbackInterface $callback, Transaction $transaction): void
    {
        try {
            /** @var string $body */
            $body = json_encode([
                'customer' => [
                    'idType' => $transaction->getCustomer()->getIdType(),
                    'id' => $transaction->getCustomer()->getId(),
                    'otp' => $transaction->getCustomer()->getOtp(),
                    'walletType' => $transaction->getCustomer()->getWalletType()->value
                ],
                'partner' => [
                    'id' => $transaction->getPartner()->getId(),
                    'idType' => $transaction->getPartner()->getIdType()->value
                ],
                'amount' => [
                    'value' => $transaction->getAmount()->getValue(),
                    'unit' => $transaction->getAmount()->getUnit()->value
                ],
                'metadata' => $transaction->getMetadata(),
                'reference' => $transaction->getReference()
            ]);

            $response = $this->client->post('https://'.$this->token->makeFQDN(self::SUFFIX),
                header: ['Authorization' => 'Bearer '. $this->token->getAccessToken(), 'Content-Type' => 'application/json'],
                body: $body
            );
            $data = $response->getData();
            if($response->isSuccess()){
                $callback->successHandler(new OneStepPayResponse($data['reference'], $data['transactionId'], PaiementResponseStatus::from($data['status']), $data['description']));
                return;
            }

            $callback->errorHandler(OmResponseError::makeFromArray($data));
        }catch (\Exception $e){
            $callback->errorHandler($e->getMessage());
        }
    }
}
