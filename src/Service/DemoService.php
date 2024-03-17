<?php
declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * DemoService
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package  App\Service
*/
class DemoService
{

    /**
     * @param ValidatorInterface $validator
     * @param string $key
    */
    public function __construct(
        protected ValidatorInterface $validator,
        protected string $key
    )
    {
    }
}