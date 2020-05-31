<?php

namespace App\Form;

use App\Entity\Users;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ModifyUsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('email')
//            ->add('roles')
//            ->add('password')
//            ->add('username')
            ->add('imageFile', VichImageType::class, [
                'label' => 'Photo de profile :',
                'required' => false,
            ])
//            ->add('updated_at')
            ->add('description', CKEditorType::class,[
                'label' => 'Description :',
                'required' => false,
            ])
            ->add('steamId', TextType::class,[
                'label' => 'Id Steam :',
                'required' => false,
            ])
            ->add('friendCode', IntegerType::class,[
                'label' => 'Code ami Steam :',
                'required' => false,
            ])
            ->add('discordTag', TextType::class,[
                'label' => 'Pseudo Discord :',
                'required' => false,
            ])
            ->add('battleTag',TextType::class,[
                'label' => 'Battle Tag :',
                'required' => false,
            ])
            ->add('xboxname',TextType::class,[
                'label' => 'Pseudo Xbox :',
                'required' => false,
            ])
            ->add('palystation',TextType::class,[
                'label' => 'Pseudo playstation : ',
                'required' => false,
            ])
            ->add('Update', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
