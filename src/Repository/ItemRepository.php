<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository implements ItemRepositoryInterface
{
    /**
     * ItemRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    /**
     * @param User $user
     * @return array|null
     */
    public function getItemsByUser(User $user): ?array
    {
        return $this->findBy(['user' => $user]);
    }

    /**
     * @param User $user
     * @param string $data
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(User $user, string $data): void
    {
        $item = new Item();
        $item->setUser($user);
        $item->setData($data);

        $this->_em->persist($item);
        $this->_em->flush();
    }

    /**
     * @param Item $item
     * @param $data
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Item $item, $data): void
    {
        $item->setData($data);

        $this->_em->persist($item);
        $this->_em->flush();
    }

    /**
     * @param Item $item
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Item $item): void
    {
        $this->_em->remove($item);
        $this->_em->flush();
    }
}
