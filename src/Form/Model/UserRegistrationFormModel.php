<?php


namespace App\Form\Model;


use App\Validator\UniqueUser;
use Doctrine\Common\Annotations\Annotation\Target;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserRegistrationFormModel
 * @package App\Form\Model
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class UserRegistrationFormModel
{
    /**
     * @Assert\NotBlank(message="Please enter an email")
     * @Assert\Email()
     * @UniqueUser()
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