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

use App\Domain\AbstractRequestResolver;
use App\Domain\Common\Exceptions\ValidatorException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestResolver
 */
class RequestResolver extends AbstractRequestResolver
{
    /**
     * @param Request $request
     *
     * @return ContactRequestInput
     *
     * @throws ValidatorException
     */
    public function resolve(Request $request): ContactRequestInput
    {
        /** @var ContactRequestInput $input */
        $input = $this->getInputFromPayload($request);
        $this->validate($input);

        return $input;
    }

    /**
     * @return string
     */
    protected function getClassInput(): string
    {
        return ContactRequestInput::class;
    }
}
