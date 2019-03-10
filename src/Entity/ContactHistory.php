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

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ContactHistory
 *
 * @ORM\Table(name="amo_contact_history")
 * @ORM\Entity()
 */
class ContactHistory extends AbstractEntity
{
    const LIST_SUBJECT_CONTACT = [
        'general_information' => 'Demande informations',
        'price_information' => 'Information sur les prix',
        'bug_report' => 'Report de bug',
    ];

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $firstname;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $lastname;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $subject;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $message;

    /**
     * ContactHistory constructor.
     *
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string $subject
     * @param string $message
     *
     * @throws \Exception
     */
    public function __construct(
        string $firstname,
        string $lastname,
        string $email,
        string $subject,
        string $message
    ) {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->subject = $subject;
        $this->message = $message;
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
