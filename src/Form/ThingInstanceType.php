<?php

namespace App\Form;

use App\Entity\ThingInstance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThingInstanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('serial', null, [
                'label' => 'Numéro de série'
            ])
            ->add('borrowDate', null, [
                'label' => "Date d'emprunt",
                'widget' => 'single_text'
            ])
            ->add('returnDate', null, [
                'label' => "Date de retour",
                'widget' => 'single_text'
            ])
            ->add('borrower', null, [
                'label' => "Emprunteur"
            ])
            ->add('booker', null, [
                'label' => "Réservé par"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ThingInstance::class,
        ]);
    }
}
