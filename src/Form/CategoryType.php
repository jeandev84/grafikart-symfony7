<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Form\Listener\FormListenerFactory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
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
            ->add('name', TextType::class, [
                'empty_data' => ''
            ])
            ->add('slug', TextType::class, [
                'required'   => false,
                'empty_data' => ''
            ])
            /*
            ->add('recipes', EntityType::class, [
                'class'        => Recipe::class,
                'choice_label' => 'title',
                'multiple'     => true,
                'expanded'     => true, # on aura un type checkboxes pour multiple selection
                'by_reference' => false
            ])
            */
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer'
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->formListenerFactory->autoSlug('name'))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->formListenerFactory->timestamps())
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
