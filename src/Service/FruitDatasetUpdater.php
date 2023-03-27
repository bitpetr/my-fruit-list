<?php

namespace App\Service;

use App\Entity\Fruit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class FruitDatasetUpdater
{

    public function __construct(
        public readonly EntityManagerInterface $em,
        public readonly DenormalizerInterface $denormalizer
    ) {
    }

    public function update(array $dataset): array
    {
        $new = $updated = [];
        foreach ($dataset as $item) {
            if ($existing = $this->em->find(Fruit::class, $item['id'])) {
                $updated[] = $this->denormalize($item, $existing);
            } else {
                $new[] = $fruit = $this->denormalize($item);
                $this->em->persist($fruit);
            }
        }
        $this->em->flush();

        return [$new, $updated];
    }

    protected function denormalize(array $item, ?Fruit $existing = null): Fruit
    {
        // Flatten the nutritions array into the main item array
        foreach ($item['nutritions'] as $key => $value) {
            $item[$key] = (float)$value;
        }
        unset($item['nutritions']);

        return $existing ? $this->denormalizer->denormalize(
            $item,
            Fruit::class,
            'array',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $existing]
        ) : $this->denormalizer->denormalize(
            $item,
            Fruit::class,
            'array',
        );
    }
}