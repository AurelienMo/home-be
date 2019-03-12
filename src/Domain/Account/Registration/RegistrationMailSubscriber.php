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

use App\Domain\Common\Helpers\MailHelper;
use App\Domain\Common\Subscribers\AbstractMailSubscriber;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class RegistrationMailSubscriber
 */
class RegistrationMailSubscriber extends AbstractMailSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            RegistrationMailEvent::ON_USER_REGISTRATION => 'onRegistration',
        ];
    }

    /**
     * @param RegistrationMailEvent $event
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function onRegistration(RegistrationMailEvent $event)
    {
        $this->mailHelper->send(
            RegistrationMailEvent::SUBJECT_MAIL,
            MailHelper::PARAMS_APPLICATION,
            [
                'email' => $event->getUser()->getUsername(),
                'name' => sprintf(
                    '%s %s',
                    $event->getUser()->getFirstName(),
                    $event->getUser()->getLastName()
                )
            ],
            RegistrationMailEvent::TEMPLATE_MAIL,
            [
                'user' => $event->getUser(),
                'url' => sprintf('/account/confirm/%s', $event->getUser()->getTokenActivation())
            ]
        );
    }
}
