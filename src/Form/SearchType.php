<?php

namespace App\Form;

use App\Entity\Ville;
use App\Data\SearchData;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('qt', TextType::class, [
                'label'=>false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])
            ->add('category', EntityType::class,[
                'label' => false,
                'required' => false,
                'class' => Category::class,
                'placeholder' => 'Choisir une spécialité',
                'attr' => ['class' => 'chosen-select']
             ])
             ->add('ville', EntityType::class,[
                'label' => false,
                'required' => false,
                'class' => Ville::class,
                'placeholder' => 'Choisir une ville',
                'attr' => ['class' => 'chosen-select']
             ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
    public function getBlockPrefix(){

        return '';
    }

}

