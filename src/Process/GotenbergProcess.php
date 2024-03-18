<?php
declare(strict_types=1);

namespace App\Process;

use Symfony\Component\Process\Process;

/**
 * GotenbergProcess
 *
 * @link https://gotenberg.dev/docs/getting-started/installation
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package  App\Process
 */
class GotenbergProcess extends Process
{
       public function __construct(array $command, ?string $cwd = null, ?array $env = null, mixed $input = null, ?float $timeout = 60)
       {
           parent::__construct($command, $cwd, $env, $input, $timeout);
       }
}