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

namespace App\Actions\Common;

use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ContactRequest
 *
 * @Route("/contact", name="contact_request", methods={"POST"})
 */
class ContactRequest
{
    /**
     * Send a contact request to HomeManagement Staff
     *
     * @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/ContactRequestInput"),
     *     description="Request payload contain all informations",
     *     required=true
     * )
     * @SWG\Response(
     *     response="201",
     *     description="Successful submit contact request"
     * )
     * @SWG\Response(
     *     response="400",
     *     description="Bad request. Check your request."
     * )
     * @SWG\Tag(name="Common")
     */
    public function __invoke()
    {
    }
}
