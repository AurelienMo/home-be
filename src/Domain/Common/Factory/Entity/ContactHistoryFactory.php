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

namespace App\Domain\Common\Factory\Entity;

use App\Entity\ContactHistory;

/**
 * Class ContactHistoryFactory
 */
class ContactHistoryFactory
{
    /**
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string $subject
     * @param string $message
     *
     * @return ContactHistory
     *
     * @throws \Exception
     */
    public static function create(
        string $firstname,
        string $lastname,
        string $email,
        string $subject,
        string $message
    ) {
        return new ContactHistory(
            $firstname,
            $lastname,
            $email,
            $subject,
            $message
        );
    }
}
