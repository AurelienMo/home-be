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

namespace App\Domain\Common\Subscribers;

use App\Domain\Common\Exceptions\ValidatorException;
use App\Responders\JsonResponder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ValidatorExceptionSubscriber
 */
class ValidatorExceptionSubscriber implements EventSubscriberInterface
{
    /** @var SerializerInterface */
    protected $serializer;

    /**
     * ValidatorExceptionSubscriber constructor.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onValidatorException',
        ];
    }

    public function onValidatorException(GetResponseForExceptionEvent $event)
    {
        if (!$event->getException() instanceof ValidatorException) {
            return;
        }

        /** @var ValidatorException $exception */
        $exception = $event->getException();

        $event->setResponse(
            JsonResponder::response(
                false,
                $this->serializer->serialize($exception->getErrors(), 'json'),
                $exception->getStatusCode()
            )
        );
    }
}
