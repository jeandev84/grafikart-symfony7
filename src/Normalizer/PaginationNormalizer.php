<?php
declare(strict_types=1);

namespace App\Normalizer;

use App\Entity\Recipe;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * PaginationNormalizer
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package  App\Normalizer
*/
class PaginationNormalizer implements NormalizerInterface
{


    /**
     * @param NormalizerInterface $normalizer
    */
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')] // on indique le service de normalization qu' on veut utiliser
        private readonly NormalizerInterface $normalizer
    )
    {
    }



    /**
     * @inheritDoc
    */
    public function normalize(mixed $object, ?string $format = null, array $context = []):
    array|string|int|float|bool|\ArrayObject|null
    {
        if (!($object instanceof PaginationInterface)) {
            throw new \RuntimeException();
        }

        return [
           'items'    => array_map(
                           fn(Recipe $recipe) => $this->normalizer->normalize($recipe, $format, $context),
                           $object->getItems()
                         ),
           'total'    => $object->getTotalItemCount(),
           'page'     => $object->getCurrentPageNumber(),
           'lastPage' => ceil($object->getTotalItemCount() / $object->getItemNumberPerPage())
        ];
    }




    /**
     * @inheritDoc
    */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        /* return $data instanceof PaginationInterface && $format === 'json'; */
        return $data instanceof PaginationInterface;
    }



    /**
     * @inheritDoc
    */
    public function getSupportedTypes(?string $format): array
    {
        // On indique que ce normilizer ne peut etre declanche
        // si et seulement si on a un object qui implements la PaginationInterface
        return [
            PaginationInterface::class => true
        ];
    }
}