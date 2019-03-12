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

use App\Responders\JsonResponder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class HttpExceptionSubscriber
 */
class HttpExceptionSubscriber implements EventSubscriberInterface
{
    /** @var SerializerInterface */
    protected $serializer;

    /**
     * HttpExceptionSubscriber constructor.
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
            KernelEvents::EXCEPTION => 'onHttpException',
        ];
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onHttpException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if (!$exception instanceof HttpExceptionInterface) {
            return;
        }
        /** @var string $datas */
        $datas = json_encode(
            [
                'code' => $exception->getStatusCode(),
                'message' => $exception->getMessage(),
            ]
        );

        $event->setResponse(
            JsonResponder::response(
                false,
                $datas,
                $exception->getStatusCode()
            )
        );
    }
}
