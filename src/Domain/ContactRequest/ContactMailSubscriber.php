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

use App\Domain\Common\Helpers\MailHelper;
use App\Domain\Common\Subscribers\AbstractMailSubscriber;
use App\Entity\ContactHistory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ContactMailSubscriber
 */
class ContactMailSubscriber extends AbstractMailSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ContactMailEvent::class => 'onSendMail',
        ];
    }

    public function onSendMail(ContactMailEvent $event)
    {
        $configuration = $this->getConfiguration($event);
        $this->mailHelper->send(
            $configuration['subject'],
            $configuration['from'],
            $configuration['to'],
            $configuration['template'],
            $configuration['extraParams']
        );
    }

    private function getConfiguration(ContactMailEvent $event)
    {
        $from = null;
        $to = null;
        $subject = null;
        $template = null;
        $extraParams = [];
        switch ($event->getType()) {
            case 'user':
                $from = MailHelper::PARAMS_APPLICATION;
                $to = [
                    'email' => $event->getContact()->getEmail(),
                    'name' => sprintf(
                        '%s %s',
                        $event->getContact()->getFirstname(),
                        $event->getContact()->getLastname()
                    ),
                ];
                $subject = ContactMailEvent::SUBJECT_TO_USER;
                $template = ContactMailEvent::TEMPLATE_TO_USER;
                $extraParams = [
                    'input' => $event->getContact(),
                ];
                break;
            case 'staff':
                $from = [
                    'email' => $event->getContact()->getEmail(),
                    'name' => sprintf(
                        '%s %s',
                        $event->getContact()->getFirstname(),
                        $event->getContact()->getLastname()
                    ),
                ];
                $to = MailHelper::PARAMS_APPLICATION;
                $subject = ContactMailEvent::SUBJECT_TO_STAFF;
                $template = ContactMailEvent::TEMPLATE_TO_STAFF;
                $extraParams = [
                    'input' => $event->getContact(),
                    'subject' => ContactHistory::LIST_SUBJECT_CONTACT[$event->getContact()->getSubject()],
                ];
                break;
        }

        return [
            'subject' => $subject,
            'template' => $template,
            'from' => $from,
            'to' => $to,
            'extraParams' => $extraParams,
        ];
    }
}
