<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use App\Entity\Contact;

class ContactConstraintType extends AbstractType
{

    public function buildForm( FormBuilderInterface $builder, array $options )
    {

        $builder
                ->add( 'name', TextType::class, [
                    'required' => true,
                    'attr' => ['pattern' => '^\w{2,32} \w{2,32}$', 'data-message' => 'Bad'],
                    'constraints' => [
                        new Regex( ['pattern' => '/^\w{2,32} \w{2,32}$/'] )
                    ],
                ] )
                ->add( 'email', EmailType::class, [
                    'required' => true,
                    'constraints' => [new Email()]] )
                ->add( 'message', TextareaType::class, [
                    'required' => true,
                    'attr' => [ 'data-pattern' => '^[a-z !@#$%^\x26*()-_+=;:\x27\x22?,\.]{10,512}$'],
                    'constraints' => [
                        new Regex( ['pattern' => '/^[a-z !@#$%^\x26*()-_+=;:\x27\x22?,\.]{10,512}$/'] )
            ]] )
        ;
    }

    public function configureOptions( OptionsResolver $resolver )
    {
        $resolver->setDefaults( array(
            'data_class' => Contact::class
        ) );
    }

}
