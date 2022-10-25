<?php

namespace App\Form;

use App\Entity\Medecin;
use App\Entity\Patient;
use App\Entity\RendezVous;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RendezVousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut',HiddenType::class,[])
            //->add('duree')            
            
            ->add('patient',EntityType::class,
            [ 'class'=>Patient::class,
            "choice_label"=>function($patient){
                return $patient->getNom()." ".$patient->getPrenom();
            }])
            ->add('medecin',EntityType::class,
            [ 'class'=>Medecin::class,
            "choice_label"=>function($medecin){
                return $medecin->getNom()." ".$medecin->getPrenom();
            }])
            ->add('description')
            ->add('semaine',TextType::class,[ "mapped"=>false ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RendezVous::class,
        ]);
    }
}
