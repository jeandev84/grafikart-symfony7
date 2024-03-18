<?php
namespace App\Security\Voter;

use App\Entity\Recipe;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class RecipeVoter extends Voter
{
    public const EDIT      = 'RECIPE_EDIT';
    public const VIEW      = 'RECIPE_VIEW';
    public const CREATE    = 'RECIPE_CREATE';
    public const LIST      = 'RECIPE_LIST';
    public const LIST_ALL  = 'RECIPE_ALL';



    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::CREATE, self::LIST, self::LIST_ALL]) ||
               (
                  in_array($attribute, [self::EDIT, self::VIEW])
                    && $subject instanceof \App\Entity\Recipe
               );
    }





    /**
     * @param string $attribute
     * @param Recipe|null $subject
     * @param TokenInterface $token
     * @return bool
    */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                // On ne peut editer la recette si on est possesseur
                return $subject->getUser()->getId() === $user->getId();
                break;

            case self::VIEW:
            case self::LIST:
            case self::CREATE:
                // Tout le monde a le droit  d' editer
                return true;
                break;
        }

        return false;
    }
}
