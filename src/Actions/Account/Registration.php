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

namespace App\Actions\Account;

use App\Domain\Account\Registration\Persister;
use App\Domain\Account\Registration\RegistrationInput;
use App\Domain\Account\Registration\RequestResolver;
use App\Domain\Common\Exceptions\ValidatorException;
use App\Responders\JsonResponder;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class Registration
 *
 * @Route("/registration", name="registration", methods={"POST"})
 */
class Registration
{
    /** @var RequestResolver */
    protected $requestResolver;

    /** @var Persister */
    protected $persister;

    /**
     * Registration constructor.
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
     * Register to application
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws ValidatorException
     * @throws \Exception
     *
     * @SWG\Response(
     *     response="201",
     *     description="Successfull registration"
     * )
     * @SWG\Response(
     *     response="400",
     *     description="Bad request. Check your request."
     * )
     * @SWG\Response(
     *     response="403",
     *     description="Forbidden. You are not allowed to perform this resource",
     *     @SWG\Schema(
     *       ref="#/definitions/HttpErrorOutput"
     * )
     * )
     */
    public function __invoke(Request $request)
    {
        /** @var RegistrationInput $input */
        $input = $this->requestResolver->resolve($request);
        $this->persister->save($input);

        return JsonResponder::response(
            false,
            null,
            Response::HTTP_CREATED
        );
    }
}
