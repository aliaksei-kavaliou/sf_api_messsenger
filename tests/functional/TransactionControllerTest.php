<?php declare(strict_types = 1);

namespace App\Tests\functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TransactionControllerTest extends WebTestCase
{
    public const URL = '/api/v1/transactions';

    public function testPostTransaction(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            self::URL,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
                "user_id": 1,
                "details": "Transaction number one",
                "receiver_account": "12345",
                "receiver_name": "Name Surname",
                "amount": 20.00,
                "currency": "eur"
            }'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue(\array_key_exists('transaction_id', \json_decode($client->getResponse()->getContent())));
    }
}
