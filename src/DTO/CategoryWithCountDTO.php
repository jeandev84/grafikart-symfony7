<?php
declare(strict_types=1);

namespace App\DTO;

/**
 * CategoryWithCountDTO
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package  App\DTO
*/
class CategoryWithCountDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $count
    )
    {
    }
}