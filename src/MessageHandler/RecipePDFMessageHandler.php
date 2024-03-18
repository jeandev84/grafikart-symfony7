<?php
namespace App\MessageHandler;

use App\Message\RecipePDFMessage;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsMessageHandler]
final class RecipePDFMessageHandler
{


    /**
     * @param string $path
     * @param string $gotenbergendpoint
     * @param UrlGeneratorInterface $urlGenerator
    */
    public function __construct(
        #[Autowire('%kernel.project_dir%/public/pdfs')]
        private readonly string $path,
        #[Autowire('%app.gotenberg_endpoint%')]
        private readonly string $gotenbergendpoint,
        private readonly UrlGeneratorInterface $urlGenerator
    )
    {
    }




    /**
     * @param RecipePDFMessage $message
     * @return void
    */
    public function __invoke(RecipePDFMessage $message): void
    {
        /*
         throw new \Error("Something went wrong");
         file_put_contents($this->path . '/'. $message->recipeId . '.pdf', '');
         http://localhost:3000/health
        */

        $process = new Process([
          'curl',
          '--request',
          'POST',
          sprintf('%s/forms/chromium/convert/url', $this->gotenbergendpoint),
          '--form',
          sprintf('url=%s', $this->generateRecipeShowUrl($message->recipeId)),
          '-o',
          $this->generateRecipePdfPath($message->recipeId)
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }





    /**
     * @param int $recipeId
     * @return string
    */
    private function generateRecipeShowUrl(int $recipeId): string
    {
        return $this->urlGenerator->generate('recipe.show', [
            'id' => $recipeId
        ], UrlGeneratorInterface::ABSOLUTE_URL);
    }




    /**
     * @param int $recipeId
     * @return string
    */
    private function generateRecipePdfPath(int $recipeId): string
    {
        return $this->path . DIRECTORY_SEPARATOR . $recipeId . '.pdf';
    }
}
