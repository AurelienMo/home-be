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

namespace App\Domain\ContactRequest;

use App\Domain\AbstractPersister;
use App\Domain\Common\Factory\Entity\ContactHistoryFactory;
use App\Entity\ContactHistory;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class Persister
 */
class Persister extends AbstractPersister
{
    /**
     * @param ContactRequestInput $input
     *
     * @throws \Exception
     */
    public function persist(ContactRequestInput $input): void
    {
        $entity = $this->createObject($input);

        try {
            $this->persistSave($entity);
        } catch (ORMInvalidArgumentException|ORMException $e) {
            throw new HttpException(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $e->getMessage()
            );
        }

         $this->eventDispatcher->dispatch(
             ContactMailEvent::class,
             new ContactMailEvent($entity, 'staff')
         );
        $this->eventDispatcher->dispatch(
            ContactMailEvent::class,
            new ContactMailEvent($entity, 'user')
        );
    }

    /**
     * @param ContactRequestInput $input
     *
     * @return ContactHistory
     *
     * @throws \Exception
     */
    private function createObject(ContactRequestInput $input)
    {
        return ContactHistoryFactory::create(
            $input->getFirstname(),
            $input->getLastname(),
            $input->getEmail(),
            $input->getSubject(),
            $input->getMessage()
        );
    }
}
