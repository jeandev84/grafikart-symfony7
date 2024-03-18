<?php
namespace App\MessageHandler;

use App\Message\RecipePDFMessage;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class RecipePDFMessageHandler
{


    /**
     * @param string $path
    */
    public function __construct(
        #[Autowire('%kernel.project_dir%/public/pdfs')]
        private readonly string $path
    )
    {
    }




    /**
     * @param RecipePDFMessage $message
     * @return void
    */
    public function __invoke(RecipePDFMessage $message)
    {
        /* throw new \Error("Something went wrong"); */
        file_put_contents($this->path . '/'. $message->recipeId . '.pdf', '');
    }
}
