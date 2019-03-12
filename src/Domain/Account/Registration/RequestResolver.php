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

use App\Domain\AbstractRequestResolver;
use App\Domain\Common\Exceptions\ValidatorException;
use App\Domain\InputInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class RequestResolver
 */
class RequestResolver extends AbstractRequestResolver
{
    /**
     * @param Request $request
     *
     * @return InputInterface|RegistrationInput
     * @throws ValidatorException
     */
    public function resolve(Request $request)
    {
        if ($this->getCurrentUser() instanceof UserInterface) {
            throw new AccessDeniedException(
                'Vous ne pouvez pas vous inscrire en étant connecté.'
            );
        }

        /** @var RegistrationInput $input */
        $input = $this->getInputFromPayload($request);
        $this->validate($input);

        return $input;
    }

    /**
     * @return string
     */
    protected function getClassInput(): string
    {
        return RegistrationInput::class;
    }
}
