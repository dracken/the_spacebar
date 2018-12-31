<?php

namespace App\Validator;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueUserValidator extends ConstraintValidator
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UniqueUserValidator constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {

        $this->userRepository = $userRepository;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $existingUser = $this->userRepository->findOneBy([
            'email' => $value
        ]);

        if (!$existingUser)
        {
            return;
        }

        /* @var $constraint App\Validator\UniqueUser */
        $this->context->buildViolation($constraint->message)
            //->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
