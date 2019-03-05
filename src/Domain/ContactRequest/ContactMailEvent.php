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

use App\Entity\ContactHistory;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class ContactMailEvent
 */
class ContactMailEvent extends Event
{
    const CONTACT_MAIL_EVENT = 'app.mail.contact';
    const SUBJECT_TO_USER = 'Demande d\'information - Confirmation de réception';
    const TEMPLATE_TO_USER = 'mails/contact/confirm_receive.html.twig';
    const SUBJECT_TO_STAFF = 'Nouvelle demande d\'informations';
    const TEMPLATE_TO_STAFF = 'mails/contact/request_contact.html.twig';

    /** @var ContactHistory */
    protected $contact;

    /** @var string */
    protected $type;

    /**
     * ContactMailEvent constructor.
     *
     * @param ContactHistory $contact
     * @param string         $type
     */
    public function __construct(
        ContactHistory $contact,
        string $type
    ) {
        $this->contact = $contact;
        $this->type = $type;
    }

    /**
     * @return ContactHistory
     */
    public function getContact(): ContactHistory
    {
        return $this->contact;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
