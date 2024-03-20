<?php

namespace App\Tests\Entity;

use App\Entity\Gamelog;
use App\Entity\Roundlog;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use DateTime;

class GamelogTest extends TestCase
{
    public function testGamelog(): void
    {
        $gamelog = new Gamelog();
        $gamelog->setName("Chen");
        $gamelog->setHands(2);
        $gamelog->setBalance(200);
        $gamelog->setId(1);

        $this->assertEquals("Chen", $gamelog->getName());
        $this->assertEquals(1, $gamelog->getId());
        $this->assertEquals(2, $gamelog->getHands());
        $this->assertEquals(200, $gamelog->getBalance());
        $this->assertInstanceOf(DateTime::class, $gamelog->getTimestamp());
        $gamelog->setTimestamp(new DateTime());
        $this->assertInstanceOf(DateTime::class, $gamelog->getTimestamp());
    }
}
