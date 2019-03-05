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

use App\Domain\Common\Helpers\MailHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class AbstractMailSubscriber
 */
abstract class AbstractMailSubscriber
{
    /** @var MailHelper */
    protected $mailHelper;

    /**
     * AbstractMailSubscriber constructor.
     *
     * @param MailHelper $mailHelper
     */
    public function __construct(
        MailHelper $mailHelper
    ) {
        $this->mailHelper = $mailHelper;
    }
}
