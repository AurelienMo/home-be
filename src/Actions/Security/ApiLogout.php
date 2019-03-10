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

namespace App\Actions\Security;

use App\Domain\Common\Exceptions\ValidatorException;
use App\Domain\Security\Logout\Persister;
use App\Domain\Security\Logout\RequestResolver;
use App\Responders\JsonResponder;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApiLogout
 *
 * @Route("/logout", name="api_logout", methods={"POST"})
 */
class ApiLogout
{
    /** @var RequestResolver */
    protected $requestResolver;

    /** @var Persister */
    protected $persister;

    /**
     * ApiLogout constructor.
     *
     * @param RequestResolver $requestResolver
     */
    public function __construct(
        RequestResolver $requestResolver,
        Persister $persister
    ) {
        $this->requestResolver = $requestResolver;
        $this->persister = $persister;
    }

    /**
     * Allow to logout from back. Remove refresh token into database
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws ValidatorException
     *
     * @SWG\Response(
     *     response="204",
     *     description="Successful response"
     * )
     * @SWG\Response(
     *     response="400",
     *     description="Bad request. Check your request."
     * )
     */
    public function __invoke(Request $request)
    {
        $input = $this->requestResolver->resolve($request);
        $this->persister->save($input);

        return JsonResponder::response(false, null, 204);
    }
}
