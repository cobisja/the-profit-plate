<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\DBAL\ParameterType;
use Symfony\Component\Uid\Uuid;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends BaseRepository
{
    protected static string $entityClassName = Product::class;

    /**
     * @return Product[]
     */
    public function getAllWithType(): array
    {
        return $this->createQueryBuilder('p')
            ->addSelect('pt')
            ->innerJoin('p.productType', 'pt')
            ->getQuery()
            ->getResult();
    }

    public function findByIdWithItsRelationships(string $id): ?Product
    {
        return $this->createQueryBuilder('p')
            ->addSelect('pt, pv, ri, r')
            ->innerJoin('p.productType', 'pt')
            ->leftJoin('p.priceVariations', 'pv')
            ->leftJoin('p.recipeIngredients', 'ri')
            ->leftJoin('ri.recipe', 'r')
            ->where('p.id = :id')
            ->setParameter('id', Uuid::fromString($id)->toBinary(), ParameterType::BINARY)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
