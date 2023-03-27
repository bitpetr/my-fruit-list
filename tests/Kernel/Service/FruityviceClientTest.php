<?php

namespace App\Tests\Kernel\Service;

use App\Service\FruityviceClient;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FruityviceClientTest extends KernelTestCase
{
    public function testGetFruits(): void
    {
        self::bootKernel();
        $client = self::getContainer()->get(FruityviceClient::class);
        $fruits = $client->getFruitAll();
        self::assertNotEmpty($fruits);
        self::assertContains(
            [
                "genus" => "Malus",
                "name" => "Apple",
                "id" => 6,
                "family" => "Rosaceae",
                "order" => "Rosales",
                "nutritions" => [
                    "carbohydrates" => 11.4,
                    "protein" => 0.3,
                    "fat" => 0.4,
                    "calories" => 52,
                    "sugar" => 10.3,
                ],
            ],
            $fruits
        );
    }
}
