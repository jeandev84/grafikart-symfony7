<?php
namespace App\Form;

use App\DTO\ContactDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Symfony\Component\Translation\t;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'empty_data' => '',
                'label' => 'contactForm.name',
                #'label' => t('contactForm.name'),
            ])
            ->add('email', EmailType::class, [
                'empty_data' => '',
                'label' => 'contactForm.email',
                #'label' => t('contactForm.email'),
            ])
            ->add('message', TextareaType::class, [
                'empty_data' => '',
                'label' => 'contactForm.message',
                #'label' => t('contactForm.message'),
            ])
            ->add('save', SubmitType::class, [
                'label' => 'contactForm.submit',
                #'label' => t('contactForm.submit'),
            ])
            ->add('service', ChoiceType::class, [
                'label' => 'contactForm.service',
                #'label' => t('contactForm.service'),
                'choices' => [
                    'Compta'  => 'compta@bash.fr',
                    'Support' => 'support@bash.fr',
                    'Marketing' => 'marketing@bash.fr',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactDTO::class,
        ]);
    }
}
