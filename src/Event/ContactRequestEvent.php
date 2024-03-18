<?php
declare(strict_types=1);

namespace App\Event;

use App\DTO\ContactDTO;

/**
 * ContactRequestEvent
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package  App\Event
*/
class ContactRequestEvent
{

   /**
     * @param ContactDTO $contactDTO
   */
   public function __construct(
       public readonly ContactDTO $contactDTO
   )
   {
   }
}