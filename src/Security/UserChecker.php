<?php


namespace App\Security;


use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{

    public function checkPreAuth(UserInterface $user)
    {

        // our user entities can be deleted or disabled. If the user is neither, we return with no action
            if (!$user->isEnabled()) {
            throw new DisabledException('User account is disabled.');

        }

        // if we got here, we throw an exception
    }

    public function checkPostAuth(UserInterface $user)
    {
        // TODO: Implement checkPostAuth() method.
    }
}