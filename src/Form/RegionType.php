<?php

namespace App\Form;

use App\Entity\Region;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('select', ChoiceType::class,[
                'choices'       => $options[ 'arregloRegiones' ],
                'choice_label'  => 'name',
                'multiple'      => true,
                'choice_value' => function($entity) {
                    //return array(
                    //    'id' => $entity->getId(),
                    //    'name' => $entity->getName(),
                    //);
                    return $entity->getId().','.$entity->getName();
                },

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'arregloRegiones' => null,
        ]);
    }
}
