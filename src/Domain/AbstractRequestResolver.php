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

namespace App\Domain;

use App\Domain\Common\Exceptions\ValidatorException;
use App\Domain\Common\Factory\ErrorsFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class AbstractRequestResolver
 */
abstract class AbstractRequestResolver
{
    /** @var SerializerInterface */
    protected $serializer;

    /** @var ValidatorInterface */
    protected $validator;

    /** @var TokenStorageInterface */
    protected $tokenStorage;

    /** @var AuthorizationCheckerInterface */
    protected $authorizationChecker;

    /**
     * AbstractRequestResolver constructor.
     *
     * @param SerializerInterface           $serializer
     * @param ValidatorInterface            $validator
     * @param TokenStorageInterface         $tokenStorage
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        TokenStorageInterface $tokenStorage,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param InputInterface $input
     *
     * @throws ValidatorException
     */
    protected function validate(InputInterface $input)
    {
        $constraintList = $this->validator->validate($input);
        if ($constraintList->count() > 0) {
            ErrorsFactory::buildErrors($constraintList);
        }
    }

    /**
     * @return InputInterface
     *
     * @throws \ReflectionException
     */
    protected function getInputInstance()
    {
        $reflectClass = new \ReflectionClass($this->getClassInput());
        $class = $reflectClass->name;

        return new $class();
    }

    /**
     * @param Request $request
     *
     * @return InputInterface|object
     */
    protected function getInputFromPayload(Request $request)
    {
        return $this->serializer->deserialize($request->getContent(), $this->getClassInput(), 'json');
    }

    /**
     * @return object|string
     */
    protected function getCurrentUser()
    {
        return $this->tokenStorage->getToken()->getUser();
    }

    /**
     * @param string $attribute
     * @param mixed  $subject
     * @param string $message
     */
    protected function checkAuthorization(string $attribute, string $message, $subject = null)
    {
        if (!$this->authorizationChecker->isGranted($attribute, $subject)) {
            throw new AccessDeniedHttpException($message);
        }
    }

    abstract protected function getClassInput(): string;
}
