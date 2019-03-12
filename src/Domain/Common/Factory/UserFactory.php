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

namespace App\Domain\Common\Factory;

use App\Entity\User;

/**
 * Class UserFactory
 */
class UserFactory
{
    /**
     * @param string $username
     * @param string $password
     * @param string $firstName
     * @param string $lastName
     *
     * @return User
     *
     * @throws \Exception
     */
    public static function create(
        string $username,
        string $password,
        string $firstName,
        string $lastName
    ) {
        return new User(
            $username,
            $password,
            $firstName,
            $lastName
        );
    }
}
