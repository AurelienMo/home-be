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

namespace App\Domain\ContactRequest;

use App\Domain\InputInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ContactRequestInput
 */
class ContactRequestInput implements InputInterface
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     */
    public $firstname;

    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     */
    public $lastname;

    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     */
    public $email;

    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     */
    public $message;

    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     */
    public $subject;

    /**
     * ContactRequestInput constructor.
     *
     * @param string|null $firstname
     * @param string|null $lastname
     * @param string|null $email
     * @param string|null $message
     * @param string|null $subject
     */
    public function __construct(
        ?string $firstname,
        ?string $lastname,
        ?string $email,
        ?string $message,
        ?string $subject
    ) {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->message = $message;
        $this->subject = $subject;
    }
}
