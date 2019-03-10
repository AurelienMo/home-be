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

use App\Domain\Common\Exceptions\ValidatorException;
use App\Domain\ContactRequest\Persister;
use App\Domain\ContactRequest\RequestResolver;
use App\Responders\JsonResponder;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ContactRequest
 *
 * @Route("/contact", name="contact_request", methods={"POST"})
 */
class ContactRequest
{
    /** @var RequestResolver */
    protected $requestResolver;

    /** @var Persister */
    protected $persister;

    /**
     * ContactRequest constructor.
     *
     * @param RequestResolver $requestResolver
     * @param Persister       $persister
     */
    public function __construct(
        RequestResolver $requestResolver,
        Persister $persister
    ) {
        $this->requestResolver = $requestResolver;
        $this->persister = $persister;
    }

    /**
     * Send a contact request to HomeManagement Staff
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws ValidatorException
     * @throws \Exception
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
    public function __invoke(Request $request)
    {
        $input = $this->requestResolver->resolve($request);
        $this->persister->persist($input);

        return JsonResponder::response(false, null, 201);
    }
}
