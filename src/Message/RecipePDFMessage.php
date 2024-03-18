<?php
namespace App\Message;

final class RecipePDFMessage
{

    /**
     * @param int $recipeId
    */
    public function __construct(
        public readonly int $recipeId
    )
    {
    }
}
