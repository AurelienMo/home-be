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

namespace App\Domain\Account\Registration;

use App\Domain\AbstractPersister;
use App\Domain\Common\Factory\UserFactory;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class Persister
 */
class Persister extends AbstractPersister
{
    /** @var EncoderFactoryInterface */
    protected $encoderFactory;

    /**
     * Persister constructor.
     *
     * @param EntityManagerInterface   $entityManager
     * @param SerializerInterface      $serializer
     * @param EventDispatcherInterface $eventDispatcher
     * @param EncoderFactoryInterface  $encoderFactory
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        EventDispatcherInterface $eventDispatcher,
        EncoderFactoryInterface $encoderFactory
    ) {
        $this->encoderFactory = $encoderFactory;
        parent::__construct(
            $entityManager,
            $serializer,
            $eventDispatcher
        );
    }

    /**
     * @param RegistrationInput $input
     *
     * @throws \Exception
     */
    public function save(RegistrationInput $input)
    {
        $user = UserFactory::create(
            $input->getUsername(),
            $this->getEncoder()->encodePassword($input->getPassword(), ''),
            $input->getFirstName(),
            $input->getLastName()
        );
        $this->persistSave($user);
        $this->eventDispatcher->dispatch(
            RegistrationMailEvent::ON_USER_REGISTRATION,
            new RegistrationMailEvent($user)
        );
    }

    /**
     * @return PasswordEncoderInterface
     */
    private function getEncoder()
    {
        return $this->encoderFactory->getEncoder(User::class);
    }
}
