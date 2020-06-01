<?php

namespace App\Form;

use App\Entity\CommentReply;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentReplyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('contenu')
//            ->add('actif')
//            ->add('username')
//            ->add('rgpd')
//            ->add('created_at')
//            ->add('commentaire')
//            ->add('usersId')
            ->add('contenu', CKEditorType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('rgpd', CheckboxType::class, [
                'label' => "J'accepte que mes informations soient stockées dans la base de données de BDE.fr pour 
                            la gestion des commentaires. J'ai bien noté qu'en aucun cas ces données ne seront 
                            cédées à des tiers."
            ])
            ->add('Envoyer', SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CommentReply::class,
        ]);
    }
}
