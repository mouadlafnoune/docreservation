<?php

namespace App\Form;

use App\Entity\Ad;
use App\Entity\Category;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AdType extends AbstractType
{

    private function getconfiguration($label,$placeholder,$options = [] ){
         return array_merge ([
             'label' => $label,
             'attr' => [
                 'placeholder' => $placeholder
             ]
         ], $options);
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class , $this->getconfiguration('Name','entrer votre nom') )
            ->add('description',TextType::class , $this->getconfiguration('Description','entrer votre description') )
            ->add('coverImage',UrlType::class , $this->getconfiguration('Image','entrer votre nom') )
            ->add('paiement',TextType::class , $this->getconfiguration('paiement','entrer votre paiement') )
            ->add('slug',TextType::class , $this->getconfiguration('Slug','entrer votre slug',[
                'required' => false
            ]))
            ->add('category', EntityType::class,[
                'class' => Category::class,
                'choice_label' => 'title',
                'attr' => ['class' => 'chosen-select']
            ]) 
            ->add('imageFile',FileType::class ,[
                'label' => false,
                'attr' => ['class' => 'upload']
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
