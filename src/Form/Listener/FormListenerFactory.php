<?php
declare(strict_types=1);

namespace App\Form\Listener;

use App\Entity\Recipe;
use DateTimeImmutable;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * FormListenerFactory
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package  App\Form\Listener
*/
class FormListenerFactory
{


    /**
     * @param SluggerInterface $slugger
    */
    public function __construct(
        protected SluggerInterface $slugger
    )
    {
    }




    /**
     * @param string $field
     * @return callable
    */
    public function autoSlug(string $field): callable
    {
        return function (PreSubmitEvent $event) use ($field) {
            $data = $event->getData();

            if (empty($data['slug'])) {
                $data['slug'] = strtolower((string)$this->slugger->slug($data[$field]));
                $event->setData($data);
            }
        };
    }



    public function timestamps(): callable
    {
        return function (PostSubmitEvent $event) {
            $data = $event->getData();

            $data->setUpdatedAt(new DateTimeImmutable());

            if (!$data->getId()) {
                $data->setCreatedAt(new DateTimeImmutable());
            }
        };
    }

}