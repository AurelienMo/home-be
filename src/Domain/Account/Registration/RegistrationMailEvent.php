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

use App\Entity\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class RegistrationMailEvent
 */
class RegistrationMailEvent extends Event
{
    const ON_USER_REGISTRATION = 'app.mail.user_registration';
    const SUBJECT_MAIL = '[Home Management] Confirmation d\'inscription';
    const TEMPLATE_MAIL = 'mails/account/registration.html.twig';

    /** @var User */
    protected $user;

    /**
     * RegistrationMailEvent constructor.
     *
     * @param User $user
     */
    public function __construct(
        User $user
    ) {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
