<?php

declare(strict_types=1);

namespace App\Tests;

use App\Components\Database\Business\Model\Fixtures;
use App\Components\Database\Persistence\Entity\FavoriteEntity;
use App\Components\Database\Persistence\Entity\UserEntity;
use App\Components\Database\Persistence\ORMSqlConnector;

use App\Components\Database\Persistence\SqlConnector;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;

class SqlConnectionTest extends TestCase
{
    private ORMSqlConnector $connector;
    private SchemaTool $schemaTool;

    protected function setUp(): void
    {
        parent::setUp();
        $_ENV['DATABASE'] = 'football_test';
        $this->connector = new ORMSqlConnector();
        $entityManager = $this->connector->getEntityManager();
        $this->schemaTool = new SchemaTool($entityManager);


        $classes = $entityManager->getMetadataFactory()->getAllMetadata();
        $this->schemaTool->createSchema($classes);
    }

    protected function tearDown(): void
    {
        $classes = $this->connector->getEntityManager()->getMetadataFactory()->getAllMetadata();
        $this->schemaTool->dropSchema($classes);

        parent::tearDown();
    }

    /**
     * @throws ORMException
     */
    public function testAddUser(): void
    {
        $entityManager = $this->connector->getEntityManager();
        echo "Connection successful.\n";


        $user = new UserEntity();
        $user->setEmail('cat@g.com');
        $user->setPassword(password_hash('password123', PASSWORD_DEFAULT));
        $user->setFirstName('Tree');
        $user->setLastName('Springfield');

        $entityManager->persist($user);
        $entityManager->flush();

        $this->assertNotEmpty($user->getId());
        $this->assertEquals('cat@g.com', $user->getEmail());
    }


    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function testAddFav(): void
    {
        $entityManager = $this->connector->getEntityManager();
        echo "Connection successful.\n";


        $user = new UserEntity();
        $user->setEmail('cat@g.com');
        $user->setPassword(password_hash('password123', PASSWORD_DEFAULT));
        $user->setFirstName('Tree');
        $user->setLastName('Springfield');

        $entityManager->persist($user);
        $entityManager->flush();

        $favorite = new FavoriteEntity();
        $favorite->setFavoritePosition(1);
        $favorite->setTeamId(1);
        $favorite->setTeamName("test");
        $favorite->setTeamCrest("CREST");
        $favorite->setUser($user);

        $entityManager->persist($favorite);
        $entityManager->flush();

        $this->assertNotEmpty($user->getId());
        $this->assertEquals('cat@g.com', $user->getEmail());
    }
}