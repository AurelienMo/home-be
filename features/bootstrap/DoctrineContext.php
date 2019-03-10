<?php

declare(strict_types=1);

/*
 * This file is part of Budget-be
 *
 * (c) Aurelien Morvan <morvan.aurelien@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use App\Domain\Common\EntityFactory\BankAccountFactory;
use App\Domain\Common\EntityFactory\UserFactory;
use App\Entity\AbstractEntity;
use App\Entity\Account;
use App\Entity\CfgBank;
use App\Entity\CfgCategoryOperation;
use App\Entity\CfgTypeOperation;
use App\Entity\GroupUser;
use App\Entity\OperationAuto;
use App\Entity\OperationManual;
use App\Entity\User;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class DoctrineContext
 */
class DoctrineContext implements Context
{
    /** @var SchemaTool */
    private $schemaTool;

    /** @var RegistryInterface */
    private $doctrine;

    /** @var KernelInterface */
    private $kernel;

    /**
     * DoctrineContext constructor.
     *
     * @param RegistryInterface            $doctrine
     * @param KernelInterface              $kernel
     * @param EncoderFactoryInterface      $encoderFactory
     */
    public function __construct(
        RegistryInterface $doctrine,
        KernelInterface $kernel
    ) {
        $this->doctrine = $doctrine;
        $this->schemaTool = new SchemaTool($this->doctrine->getManager());
        $this->kernel = $kernel;
    }

    /**
     * @BeforeScenario
     *
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    public function clearDatabase()
    {
        $this->schemaTool->dropSchema($this->doctrine->getManager()->getMetadataFactory()->getAllMetadata());
        $this->schemaTool->createSchema($this->doctrine->getManager()->getMetadataFactory()->getAllMetadata());
    }

    /**
     * @return ObjectManager
     */
    public function getManager()
    {
        return $this->doctrine->getManager();
    }

    /**
     * @Given I load production file
     */
    public function iLoadProductionFile()
    {
        $app = new Application($this->kernel);
        $app->setAutoExit(false);
        $input = new ArrayInput(
            [
                'command' => 'app:load-prod-fixtures',
            ]
        );
        $output = new BufferedOutput();
        $app->run($input, $output);
    }

    /**
     * @param AbstractEntity $entity
     * @param string         $uuid
     *
     * @throws ReflectionException
     */
    private function setUuid(AbstractEntity $entity, string $uuid)
    {
        $reflection = new \ReflectionClass($entity);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($entity, $uuid);
    }

    /**
     * @Then :number entries should be exist into database for :class object
     */
    public function entriesShouldBeExistIntoDatabaseForObject($number, $class)
    {
        $count = $this->getManager()->getRepository($class)
                                    ->findAll();

        if (count($count) !== (int) $number) {
            throw new Exception(
                sprintf("%s object(s) has found into database, %s expected", count($count), (int) $number)
            );
        }
    }

}
