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

namespace App\Domain\Common\Exceptions;

/**
 * Class ValidatorException
 */
class ValidatorException extends \Exception
{
    /** @var int */
    private $statusCode;

    /** @var array */
    private $errors;

    /**
     * ValidatorException constructor.
     *
     * @param int   $statusCode
     * @param array $errors
     */
    public function __construct(
        int $statusCode,
        array $errors
    ) {
        $this->statusCode = $statusCode;
        $this->errors = $errors;
        parent::__construct();
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
