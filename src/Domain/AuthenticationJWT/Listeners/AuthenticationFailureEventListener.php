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

namespace App\Domain\AuthenticationJWT\Listeners;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;

/**
 * Class AuthenticationFailureEventListener
 */
class AuthenticationFailureEventListener
{
    /**
     * @param AuthenticationFailureEvent $event
     */
    public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event)
    {
        $data = 'Identifiants invalides.';

        $response = new JWTAuthenticationFailureResponse($data);

        $event->setResponse($response);
    }
}
