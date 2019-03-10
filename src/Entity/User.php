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

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 *
 * @ORM\Table(name="amo_user")
 * @ORM\Entity()
 */
class User extends AbstractEntity implements UserInterface
{
    const STATUS_ENABLE = 'enable';
    const STATUS_PENDING_VALIDATION = 'pending_validation';
    const STATUS_LOCK = 'lock';

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $username;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    protected $roles;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $lastName;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $tokenActivation;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $status;

    /**
     * User constructor.
     *
     * @param string $username
     * @param string $password
     * @param string $firstName
     * @param string $lastName
     * @param string $tokenActivation
     *
     * @throws \Exception
     */
    public function __construct(
        string $username,
        string $password,
        string $firstName,
        string $lastName,
        string $tokenActivation
    ) {
        $this->username = $username;
        $this->password = $password;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->tokenActivation = $tokenActivation;
        $this->roles[] = 'ROLE_USER';
        $this->status = self::STATUS_PENDING_VALIDATION;
        parent::__construct();
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string|null
     */
    public function getTokenActivation(): ?string
    {
        return $this->tokenActivation;
    }

    public function eraseCredentials()
    {
        return;
    }

    public function getSalt()
    {
        return null;
    }

    public function enable()
    {
        $this->status = self::STATUS_ENABLE;
        $this->tokenActivation = null;
    }
}
