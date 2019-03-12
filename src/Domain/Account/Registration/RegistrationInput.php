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

use App\Domain\Common\Validators\UniqueEntityInput;
use App\Domain\InputInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RegistrationInput
 *
 * @UniqueEntityInput(
 *     class="App\Entity\User",
 *     fields={"username"},
 *     message="Cet utilisateur existe déjà."
 * )
 */
class RegistrationInput implements InputInterface
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     */
    protected $firstName;

    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     */
    protected $lastName;

    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message="Le nom d'utilisateur doit être au format email."
     * )
     */
    protected $username;

    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min="8",
     *     minMessage="Votre mot de passe doit faire au moins 8 caractères."
     * )
     * @Assert\Regex(
     *     pattern="/^(?=(?:.*[A-Z]){1,})(?=(?:.*[a-z]){1,})(?=(?:.*\d){1,})(?=(?:.*[!@#$%^&*()\-_=+{};:,<.>]){1,})([A-Za-z0-9!@#$%^&*()\-_=+{};:,<.>]{8,})$/",
     *     message="Votre mot de passe doit contenir au minimum 8 caracteres, 1 majuscule ainsi qu'un caractere special"
     * )
     */
    protected $password;

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     */
    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string|null $username
     */
    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }
}
