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

namespace App\Domain\Security\Logout;

use App\Domain\InputInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class LogoutInput
 */
class LogoutInput implements InputInterface
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     */
    protected $refreshToken;

    /**
     * @return string|null
     */
    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    /**
     * @param string|null $refreshToken
     */
    public function setRefreshToken(?string $refreshToken): void
    {
        $this->refreshToken = $refreshToken;
    }
}
