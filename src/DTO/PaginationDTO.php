<?php
declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;


/**
 * PaginationDTO
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package  App\DTO
*/
class PaginationDTO
{
    public function __construct(
        #[Assert\Positive()]
        public readonly ?int $page = 1
    )
    {
    }
}