<?php

declare(strict_types=1);

namespace App\Tests;

use App\Components\Database\Business\Model\Fixtures;
use App\Components\Database\Persistence\Entity\FavoriteEntity;
use App\Components\Database\Persistence\Entity\UserEntity;
use App\Components\Database\Persistence\ORMSqlConnector;

use App\Components\Database\Persistence\SchemaBuilder;
use App\Components\Database\Persistence\SqlConnector;
use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\User\Persistence\UserEntityManager;
use App\Components\UserFavorite\Persistence\DTO\FavoriteDTO;
use App\Components\UserFavorite\Persistence\UserFavoriteEntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;

class SqlConnectionTest extends TestCase
{
    private ORMSqlConnector $connector;
//    private SchemaTool $schemaTool;

    private UserEntityManager $userEntityManager;
    private UserFavoriteEntityManager $userFavoriteEntityManager;
    private SchemaBuilder $schemaBuilder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->connector = new ORMSqlConnector();

        $this->schemaBuilder = new SchemaBuilder($this->connector);
        $this->schemaBuilder->createSchema();

        /*
                $this->schemaTool = new SchemaTool($entityManager);
                $classes = $entityManager->getMetadataFactory()->getAllMetadata();
                $this->schemaTool->createSchema($classes);
        */
        $this->userEntityManager = new UserEntityManager($this->connector);
        $this->userFavoriteEntityManager = new UserFavoriteEntityManager($this->connector);
    }

    protected function tearDown(): void
    {
        $this->schemaBuilder->clearDatabase();
        parent::tearDown();
    }

    /**
     * @throws ORMException
     */
    public function testAddUser(): void
    {
        $entityManager = $this->connector->getEntityManager();



        $user = new UserEntity();
        $user->setEmail('cat@g.com');
        $user->setPassword(password_hash('password123', PASSWORD_DEFAULT));
        $user->setFirstName('Tree');
        $user->setLastName('Springfield');


        $userDTO = new UserDTO(null, 'Tim', 'Gabel', 'test2@g.com', 'aegew');
        $this->userEntityManager->saveUser($userDTO);
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



        $user = new UserEntity();
        $user->setEmail('cat@g.com');
        $user->setPassword(password_hash('password123', PASSWORD_DEFAULT));
        $user->setFirstName('Tree');
        $user->setLastName('Springfield');

        $entityManager->persist($user);
        $entityManager->flush();

        $favorite = new FavoriteEntity();
        $favorite->setFavoritePosition(2);
        $favorite->setTeamId(2);
        $favorite->setTeamName("test");
        $favorite->setTeamCrest("CREST");
        $favorite->setUserIdFk($user->getId());


        $favoriteDto = new FavoriteDTO(1, 'testName', 'crest', 2);
        $userDTO = new UserDTO(null, 'Tim', 'Gabel', 'test2@g.com', 'aegew');
        $this->userEntityManager->saveUser($userDTO);
        $userDTO2 = new UserDTO(1, '', '', '', '');


        $entityManager->persist($favorite);
        $entityManager->flush();

        $this->userFavoriteEntityManager->saveUserFavorite($userDTO2, $favoriteDto);

        $this->assertNotEmpty($user->getId());
        $this->assertEquals('cat@g.com', $user->getEmail());
    }
}