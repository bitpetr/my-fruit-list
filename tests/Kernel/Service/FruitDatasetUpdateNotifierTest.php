<?php

namespace App\Tests\Kernel\Service;

use App\Entity\Fruit;
use App\Service\FruitDatasetUpdateNotifier;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FruitDatasetUpdateNotifierTest extends KernelTestCase
{
    public function testSendUpdateNotification()
    {
        self::bootKernel();

        $newFruits = [
            (new Fruit())->setName('Apple')->setFamily('Rosaceae'),
            (new Fruit())->setName('Orange')->setFamily('Rutaceae'),
        ];
        $updatedFruits = [
            (new Fruit())->setName('Banana')->setFamily('Musaceae'),
        ];

        self::getContainer()->get(FruitDatasetUpdateNotifier::class)
            ->notify($newFruits, $updatedFruits);

        $email = self::getMailerMessage();

        // Check if the email was sent and contains the required data
        $this->assertNotNull($email, "Email not sent");
        $this->assertSame('Fruits database updated', $email->getSubject());
        $this->assertEmailTextBodyContains($email, '2 new fruit(s)');
        $this->assertEmailTextBodyContains($email, '1 existing fruit(s)');
        $this->assertEmailTextBodyContains($email, 'Apple (Rosaceae)');
        $this->assertEmailTextBodyContains($email, 'Orange (Rutaceae)');
        $this->assertEmailTextBodyContains($email, 'Banana (Musaceae)');
    }
}