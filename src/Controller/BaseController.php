<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;

/**
 * Class BaseController
 * @package app\Controller
 * @method User|null getUser()
 */
abstract class BaseController extends AbstractController
{
    /* // Overridden by the DocBlock declaration
    protected function getUser(): User
    {
        return parent::getUser();
    }
    */
}