<?php

namespace App\Tests\Kernel\Service;

use App\Entity\Fruit;
use App\Service\FruitDatasetUpdater;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FruitDatasetUpdaterTest extends KernelTestCase
{
    protected function tearDown():void
    {
        $this->truncateEntities([Fruit::class]);
        parent::tearDown();
    }

    /**
     * @dataProvider datasetProvider
     */
    public function testUpdate(array $dataset, array $expected): void
    {
        self::bootKernel();

        $updater = static::getContainer()->get(FruitDatasetUpdater::class);
        [$new, $updated] = $updater->update($dataset);
        self::assertSame([count($new), count($updated)], $expected);
        if (!empty($dataset)) {
            $fruit = $this->getEntityManager()->find(Fruit::class, $dataset[0]['id']);
            self::assertNotNull($fruit);
        }
    }

    public function datasetProvider(): \Generator
    {
        yield 'empty' => [[], [0, 0]];
        yield 'normal' => [
            [
                [
                    "genus" => "Fragaria",
                    "name" => "Strawberry",
                    "id" => 3,
                    "family" => "Rosaceae",
                    "order" => "Rosales",
                    "nutritions" => [
                        "carbohydrates" => 5.5,
                        "protein" => 0,
                        "fat" => 0.4,
                        "calories" => 29,
                        "sugar" => 5.4,
                    ],
                ],
                [
                    "genus" => "Musa",
                    "name" => "Banana",
                    "id" => 2,
                    "family" => "Musaceae",
                    "order" => "Zingiberales",
                    "nutritions" => [
                        "carbohydrates" => 22,
                        "protein" => 0,
                        "fat" => 0.2,
                        "calories" => 96,
                        "sugar" => 17.2,
                    ],
                ],
            ],
            [2, 0],
        ];
    }

    private function truncateEntities(array $entities)
    {
        $connection = $this->getEntityManager()->getConnection();
        $databasePlatform = $connection->getDatabasePlatform();
        foreach ($entities as $entity) {
            $query = $databasePlatform->getTruncateTableSQL(
                $this->getEntityManager()->getClassMetadata($entity)->getTableName()
            );
            $connection->executeQuery($query);
        }
    }

    private function getEntityManager(): EntityManagerInterface
    {
        return self::getContainer()->get(EntityManagerInterface::class);
    }
}
