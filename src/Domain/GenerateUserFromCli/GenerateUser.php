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

namespace App\Domain\GenerateUserFromCli;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

/**
 * Class GenerateUser
 */
class GenerateUser extends Command
{
    const LIST_FIELDS = [
        'username' => null,
        'password' => null,
        'firstName' => null,
        'lastName' => null,
        'status' => null,
    ];

    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var EncoderFactoryInterface */
    protected $encoderFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        EncoderFactoryInterface $encoderFactory
    ) {
        $this->entityManager = $entityManager;
        $this->encoderFactory = $encoderFactory;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:generate-user')
            ->setDescription('Allow to create user from command line');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     *
     * @throws \Exception
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $datasUser = self::LIST_FIELDS;
        foreach ($datasUser as $key => $value) {
            if ($key === 'status') {
                $question = new ChoiceQuestion(
                    'Do you want enable user ?',
                    ['Yes', 'No']
                );
                $datasUser['status'] = $this->getQuestionHelper()->ask($input, $output, $question);
            } else {
                $question = new Question(sprintf('Please enter value for %s field : ', $key));
                $datasUser[$key] = $this->getQuestionHelper()->ask($input, $output, $question);
            }
        }

        $user = new User(
            $datasUser['username'],
            $this->getUserEncoder()->encodePassword($datasUser['password'], ''),
            $datasUser['firstName'],
            $datasUser['lastName']
        );
        if ($datasUser['status'] === 'Yes') {
            $user->enable();
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    protected function getQuestionHelper()
    {
        return $this->getHelper('question');
    }

    /**
     * @return PasswordEncoderInterface
     */
    private function getUserEncoder()
    {
        return $this->encoderFactory->getEncoder(User::class);
    }
}
