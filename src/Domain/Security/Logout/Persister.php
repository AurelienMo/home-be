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

namespace App\Domain\Security\Logout;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class Persister
 */
class Persister
{
    /** @var KernelInterface */
    protected $kernel;

    /**
     * Persister constructor.
     *
     * @param KernelInterface $kernel
     */
    public function __construct(
        KernelInterface $kernel
    ) {
        $this->kernel = $kernel;
    }

    public function save(LogoutInput $input)
    {
        $this->buildCommand($input);
    }

    /**
     * @param LogoutInput $input
     *
     * @return string
     * @throws \Exception
     */
    private function buildCommand(LogoutInput $input)
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(
            [
                'command' => 'gesdinet:jwt:revoke',
                'refresh_token' => $input->getRefreshToken()
            ]
        );
        $output = new BufferedOutput();

        $application->run($input, $output);
    }
}
