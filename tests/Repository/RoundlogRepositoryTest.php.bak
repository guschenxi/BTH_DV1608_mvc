<?php

namespace App\Tests\Repository;

use App\Entity\Roundlog;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RoundlogRepositoryTest extends KernelTestCase
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
            ->getRepository(Roundlog::class)
            ->find(1)
        ;
        $this->assertEquals(3, $item->getGamelogId());
    }
}
