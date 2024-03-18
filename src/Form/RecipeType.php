<?php
namespace App\Form;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Form\Listener\FormListenerFactory;
use DateTimeImmutable;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Sequentially;

class RecipeType extends AbstractType
{


    /**
     * @param FormListenerFactory $formListenerFactory
    */
    public function __construct(
        protected FormListenerFactory $formListenerFactory
    )
    {
    }



    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'empty_data' => '', // la valeur par defaut au cas ou le champs est vide
            ])
            ->add('slug', TextType::class, [
                'required' => false
            ])
            ->add('thumbnailFile', FileType::class, [
                'required' => false
            ])
            ->add('category', EntityType::class, [
                'class'        => Category::class,
                'choice_label' => 'name',
                'autocomplete' => true
            ])
            /*
            ->add('category', CategoryAutocompleteField::class)
            */
            ->add('content', TextareaType::class, [
                'empty_data' => '', // la valeur par default au cas ou le champs est vide
            ])
            ->add('duration')
            // by_reference (permet d' interagir avec addSomething($object), removeSomething($object) ...)
            ->add('quantities', CollectionType::class, [
                'entry_type'   => QuantityType::class,
                'allow_add'    => true, // On donne la possibilite d' en rajouter
                'allow_delete' => true, // On donne la possibilite de pouvoir supprimer
                'by_reference' => false, // false afin qu' il interagisse avec les methods add(), remove() ...
                'entry_options' => [ // configure chaque option
                    'label' => false, // ici on veut que chaque entree n' est pas un label
                ],
                'attr' => [
                    // on donne le nom de notre controller situe ./assets/controllers/... sans le suffix controller
                    'data-controller' => 'form-collection',
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer'
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->formListenerFactory->autoSlug('title'))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->formListenerFactory->timestamps())
        ;
    }




    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class
        ]);
    }
}
