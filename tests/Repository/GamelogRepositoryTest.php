<?php

namespace App\Tests\Repository;

use App\Entity\Gamelog;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GamelogRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;
    }

    public function testSearchByName(): void
    {
        $item = $this->entityManager
            ->getRepository(Gamelog::class)
            ->find(1)
        ;
        $this->assertSame("Spelare", $item->getName());
    }
}
