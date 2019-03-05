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

namespace App\Domain\Common\Helpers;

use Twig\Environment;

/**
 * Class MailHelper
 */
class MailHelper
{
    const PARAMS_APPLICATION = [
        'email' => 'contact@home-management.com',
        'name' => 'L\'équipe Home Management',
    ];

    /** @var \Swift_Mailer */
    protected $mailer;

    /** @var Environment */
    protected $templating;

    /**
     * MailHelper constructor.
     *
     * @param \Swift_Mailer $mailer
     * @param Environment   $templating
     */
    public function __construct(
        \Swift_Mailer $mailer,
        Environment $templating
    ) {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * @param string $subject
     * @param array  $from
     * @param array  $to
     * @param string $template
     * @param array  $params
     * @param array  $attachments
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function send(
        string $subject,
        array $from,
        array $to,
        string $template,
        array $params = [],
        array $attachments = []
    ) {
        $message = new \Swift_Message();
        $message
            ->setSubject($subject)
            ->setFrom($from['email'], $from['name'] ?? null)
            ->setTo($to['email'], $to['name'] ?? null)
            ->setBody(
                $this->templating->render(
                    $template,
                    $params
                ),
                'text/html'
            );

        $this->mailer->send($message);
    }

}
