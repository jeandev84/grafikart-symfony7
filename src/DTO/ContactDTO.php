<?php
declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * ContactDTO
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package  App\DTO
 */
class ContactDTO
{

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 200)]
    public string $name = '';


    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email = '';



    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 200)]
    public string $message = '';



    #[Assert\NotBlank]
    public string $service = '';
}