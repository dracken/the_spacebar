<?php


namespace App\Form\Model;


use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserRegistrationFormModel
 * @package App\Form\Model
 *
 * @UniqueEntity(
 *     fields={"email"},
 *     message="That user already exists"
 * )
 */
class UserRegistrationFormModel
{
    /**
     * @Assert\NotBlank(message="Please enter an email")
     * @Assert\Email()
     */
    public $email;

    /**
     * @Assert\NotBlank(message="Choose a password!")
     * @Assert\Length(min=5, minMessage="Come on, you can theink of a password longer than that!")
     */
    public $plainPassword;

    /**
     * @Assert\IsTrue(message="I know, it's silly, but you must agree to our terms.")
     */
    public $agreeTerms;
}