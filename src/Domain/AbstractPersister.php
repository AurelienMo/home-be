<?php

declare(strict_types=1);

/*
 * This file is part of home-management-back
 *
 * (c) Aurelien Morvan <morvan.aurelien@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain;

use App\Entity\AbstractEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class AbstractPersister
 */
abstract class AbstractPersister
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var SerializerInterface */
    protected $serializer;

    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /**
     * AbstractPersister constructor.
     *
     * @param EntityManagerInterface   $entityManager
     * @param SerializerInterface      $serializer
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param AbstractEntity $entity
     * @param bool           $isNew
     */
    protected function persistSave(AbstractEntity $entity, bool $isNew = true)
    {
        if ($isNew) {
            $this->entityManager->persist($entity);
        }

        $this->entityManager->flush();
    }
}
