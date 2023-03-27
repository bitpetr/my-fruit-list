<?php

namespace App\Service;

use App\Entity\Fruit;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\NumberColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableFactory;

class FruitsDatatableFactory
{
    public function __construct(private readonly DataTableFactory $baseFactory)
    {
    }

    public function create(bool $favorites = false): DataTable
    {
        $table = $this->baseFactory->create()
            ->setTemplate('fruit_dt.html.twig')
            ->add('id', NumberColumn::class, ['label' => 'ID'])
            ->add('name', TextColumn::class, ['label' => 'Name', 'searchable' => true, 'globalSearchable' => true])
            ->add('genus', TextColumn::class, ['label' => 'Genus'])
            ->add('family', TextColumn::class, ['label' => 'Family'])
            ->add('order', TextColumn::class, ['label' => 'Order'])
            ->add('carbohydrates', NumberColumn::class, ['label' => 'Carbohydrates'])
            ->add('protein', NumberColumn::class, ['label' => 'Protein'])
            ->add('fat', NumberColumn::class, ['label' => 'Fat'])
            ->add('calories', NumberColumn::class, ['label' => 'Calories'])
            ->add('sugar', NumberColumn::class, ['label' => 'Sugar'])
            ->add('isFavorite', TextColumn::class, [
                'label' => '',
                'render' => function ($value, $context) {
                    return sprintf(
                        '<span class="fav-button" title="%s" data-controller="fav-button" '
                        . 'data-action="click->fav-button#toggle">%s</span>',
                        $value ? 'Unfavorite' : 'Favorite',
                        $value ? '★' : '☆'
                    );
                }
            ])
            ->addOrderBy('id');

        if ($favorites) {
            $table->setName('fruits-fav')
                ->createAdapter(ORMAdapter::class, [
                    'entity' => Fruit::class,
                    'query' => fn(QueryBuilder $builder) => $builder
                        ->select('f')
                        ->from(Fruit::class, 'f')
                        ->where($builder->expr()->eq('f.isFavorite', true))
                ]);
        } else {
            $table->setName('fruits')->createAdapter(ORMAdapter::class, ['entity' => Fruit::class]);
        }

        return $table;
    }

}