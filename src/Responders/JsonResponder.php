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

namespace App\Responders;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class JsonResponder
 */
class JsonResponder
{
    /**
     * @param bool        $isCacheable
     * @param string|null $datas
     * @param int         $statusCode
     * @param array       $additionalHeaders
     *
     * @return Response
     */
    public static function response(
        bool $isCacheable = true,
        ?string $datas = null,
        int $statusCode = Response::HTTP_OK,
        array $additionalHeaders = []
    ) {
        $response = new Response(
            $datas,
            $statusCode,
            array_merge(
                [
                    'Content-Type' => 'application/json'
                ],
                $additionalHeaders
            )
        );

        if ($isCacheable) {
            $response
                ->setPublic()
                ->setMaxAge(3600)
                ->setSharedMaxAge(3600);
        }

        return $response;
    }
}
