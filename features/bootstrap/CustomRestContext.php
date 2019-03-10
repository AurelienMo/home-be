<?php

declare(strict_types=1);

/*
 * This file is part of Home Management BE
 *
 * (c) Aurelien Morvan <morvan.aurelien@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Behat\Gherkin\Node\PyStringNode;
use Behatch\Context\RestContext;

/**
 * Class CustomRestContext
 */
class CustomRestContext extends RestContext
{
    /**
     * @param $arg1
     * @param $arg2
     * @param $arg3
     * @param $arg4
     * @param $arg5
     * @param $arg6
     * @param PyStringNode $string
     *
     * @return mixed
     *
     * @When After authentication on url :arg1 with method :arg2 as user :arg3 with password :arg4, I send a :arg5 request to :arg6 with body:
     */
    public function afterAuthenticationOnUrlWithMethodAsUserWithPasswordISendARequestToWithBody(
        $arg1,
        $arg2,
        $arg3,
        $arg4,
        $arg5,
        $arg6,
        PyStringNode $string
    ) {
        $requestLogin = $this->request->send(
            $arg2,
            $this->locatePath($arg1),
            [],
            [],
            json_encode(
                [
                    'username' => $arg3,
                    'password' => (string) $arg4,
                ]
            ),
            ['CONTENT_TYPE' => 'application/json']
        );
        $datas = json_decode($requestLogin->getContent(), true);
        $response = $this->request->send(
            $arg5,
            $this->locatePath($arg6),
            [],
            [],
            $string !== null ? $string->getRaw() : null,
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_Authorization' => sprintf('Bearer %s', $datas['token'])
            ]
        );
        return $response;
    }

    /**
     * @When Send auth request with method :arg1 request to :arg2 with username :arg3 and password :arg4
     */
    public function sendARequestToWithBody($arg1, $arg2, $arg3, $arg4)
    {
        $requestLogin = $this->request->send(
            $arg1,
            $this->locatePath($arg2),
            [],
            [],
            json_encode(
                [
                    'username' => $arg3,
                    'password' => (string) $arg4,
                ]
            ),
            ['CONTENT_TYPE' => 'application/json']
        );
        return $requestLogin->getContent();
    }

    /**
     * @Then the response should be equal to following file :file
     *
     * @throws Exception
     */
    public function theResponseShouldBeEqualToFollowingFile($file)
    {
        $contentFileExpected = file_get_contents(__DIR__.'/../scenarios/'.$file);
        $actualContent = $this->request->getContent();

        if (json_decode($contentFileExpected, true) !== json_decode($actualContent, true)) {
            throw new Exception(
                sprintf("'%s' expected", $actualContent)
            );
        }
    }
}
