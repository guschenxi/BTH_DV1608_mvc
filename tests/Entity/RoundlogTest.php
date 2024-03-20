<?php

namespace App\Tests\Entity;

use App\Entity\Roundlog;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use DateTime;

class RoundlogTest extends TestCase
{
    public function testRoundlog(): void
    {
        $roundlog = new Roundlog();
        $roundlog->setGamelogId(2);
        $roundlog->setWinhands(2);
        $roundlog->setDifference(200);
        $roundlog->setNewbalance(200);

        $this->assertEquals(null, $roundlog->getId());
        $this->assertEquals(2, $roundlog->getGamelogId());
        $this->assertEquals(2, $roundlog->getWinhands());
        $this->assertEquals(200, $roundlog->getDifference());
        $this->assertEquals(200, $roundlog->getNewbalance());
        $this->assertInstanceOf(DateTime::class, $roundlog->getTimestamp());
        $roundlog->setTimestamp(new DateTime());
        $this->assertInstanceOf(DateTime::class, $roundlog->getTimestamp());
    }
}
