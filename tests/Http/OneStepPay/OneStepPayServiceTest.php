<?php

namespace Tm\OrangeMoneySDK\Test\Http\OneStepPay;

use PHPUnit\Framework\TestCase;
use Tm\OrangeMoneySDK\Domain\Entity\Amount;
use Tm\OrangeMoneySDK\Domain\Entity\Customer;
use Tm\OrangeMoneySDK\Domain\Entity\Enum\IdType;
use Tm\OrangeMoneySDK\Domain\Entity\Enum\PaiementResponseStatus;
use Tm\OrangeMoneySDK\Domain\Entity\Enum\WalletType;
use Tm\OrangeMoneySDK\Domain\Entity\OmResponseError;
use Tm\OrangeMoneySDK\Domain\Entity\Transaction;
use Tm\OrangeMoneySDK\Http\io\HttpClientInterface;
use Tm\OrangeMoneySDK\Http\io\HttpClientResponse;
use Tm\OrangeMoneySDK\Http\io\HttpResponseCallbackInterface;
use Tm\OrangeMoneySDK\Http\OneStepPay\OneStepPayResponse;
use Tm\OrangeMoneySDK\Http\OneStepPay\OneStepPayService;
use Tm\OrangeMoneySDK\Http\Token\Token;

class OneStepPayServiceTest extends TestCase
{
    public function testValidPass()
    {
        $callback = $this->getMockBuilder(HttpResponseCallbackInterface::class)->getMock();

        $token = new Token('access', 'refresh', false);
        $client = $this->getMockBuilder(HttpClientInterface::class)->getMock();
        $client->expects($this->once())->method('post')
            ->with(
                'https://api.sandbox.orange-sonatel.com/api/eWallet/v1/payments/onestep',
                ['Authorization' => 'Bearer access', 'Content-Type' => 'application/json'],
                [],
                json_encode([
                    'customer' => [
                        'idType' => 'MSISDN',
                        'id' => '780000000',
                        'otp' => '000000',
                        'walletType' => 'INTERNATIONAL'
                    ],
                    'partner' => [
                        'id' => '444444',
                        'idType' => 'CODE'
                    ],
                    'amount' => [
                        'value' => 8,
                        'unit' => 'XOF'
                    ],
                    'metadata' => ['test' => 'testValue'],
                    'reference' => 'reference'
                ])
            )->willReturn(new HttpClientResponse(true, []));

        $service = new OneStepPayService($client, $token);
        $service->execute($callback, $this->getTransaction());
    }

    public function testSuccessCallbackIfValidResponse()
    {
        $client = $this->getMockBuilder(HttpClientInterface::class)->getMock();
        $client->expects($this->once())->method('post')
            ->willReturn(new HttpClientResponse(true, [
                'reference' => '123',
                'transactionId' => 'MP000000.0000.000000',
                'status' => 'SUCCESS',
                'description' => 'description'
            ]));

        $callback = $this->getMockBuilder(HttpResponseCallbackInterface::class)->getMock();
        $callback->expects($this->once())
            ->method('successHandler')
            ->with(new OneStepPayResponse('123', 'MP000000.0000.000000', PaiementResponseStatus::SUCCESS, 'description'));
        $callback->expects($this->never())->method('errorHandler');

        $token = new Token('access', 'refresh', false);

        $service = new OneStepPayService($client, $token);
        $service->execute($callback, $this->getTransaction());
    }

    public function testSuccessCallbackIfInValidResponse()
    {
        $data = [
            'type' => '/bad-request',
            'title' => 'Bad Request',
            'instance' => '/api/eWallet/v1/payments/onestep',
            'status' => '400',
            'code' => '24',
            'detail' => 'failed'
        ];

        $client = $this->getMockBuilder(HttpClientInterface::class)->getMock();
        $client->expects($this->once())->method('post')
            ->willReturn(new HttpClientResponse(false, $data));

        $callback = $this->getMockBuilder(HttpResponseCallbackInterface::class)->getMock();
        $callback->expects($this->once())
            ->method('errorHandler')
            ->with(OmResponseError::makeFromArray($data));
        $callback->expects($this->never())->method('successHandler');

        $token = new Token('access', 'refresh', false);

        $service = new OneStepPayService($client, $token);
        $service->execute($callback, $this->getTransaction());
    }

    private function getTransaction(): Transaction
    {
        $transaction = Transaction::forOneStepPay(
            '444444',
            new Customer('780000000', '000000', walletType: WalletType::INTERNATIONAL),
            new Amount(8),
            'reference'
        );
        $transaction->setMetadata(['test' => 'testValue']);
        return $transaction;
    }
}
