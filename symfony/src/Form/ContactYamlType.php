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
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Contact;

class ContactYamlType extends AbstractType
{

    private $validatorMetadata;

    public function __construct( ValidatorInterface $validator )
    {
        $metadata = $validator
                ->getMetadataFor( 'App\Entity\Contact' );
        $this->validatorMetadata = $metadata;
    }

    public function buildForm( FormBuilderInterface $builder, array $options )
    {

        $builder
                ->add( 'name', TextType::class, [
                    'translation_domain' => false,
                    'required' => true,
                    'attr' => $this->getHtmlPatternAndMessage( 'name' ),
                    'constraints' => [
                        new Regex( $this->getPatternAndMessage( 'name' ) )
                    ]
                ] )
                ->add( 'email', EmailType::class, [
                    'required' => true,
                    'constraints' => [new Email()]]
                )
                ->add( 'message', TextareaType::class, [
                    'required' => true,
                    'attr' => $this->getHtmlPatternAndMessage( 'message', true ),
                    'constraints' => [
                        new Regex( $this->getPatternAndMessage( 'message' ) )
                    ]
                ] )
        ;
    }

    public function configureOptions( OptionsResolver $resolver )
    {
        $resolver->setDefaults( array(
            'data_class' => Contact::class
        ) );
    }

    function getPatternAndMessage( String $field )
    {
        $metadata = $this->validatorMetadata->getPropertyMetadata( $field );
        $constraints = $metadata[0]->getConstraints();
        foreach( $constraints as $c )
        {
            if( property_exists( $c, 'pattern' ) )
            {
                return ['pattern' => $c->pattern, 'message' => $c->message];
            }
        }
        die( 'Missing pattern' );
    }

    function getHtmlPatternAndMessage( String $field, Bool $data = false )
    {
        $patternAttribute = $data === false ? 'pattern' : 'data-pattern';
        $metadata = $this->validatorMetadata->getPropertyMetadata( $field );

        $constraints = $metadata[0]->getConstraints();
        foreach( $constraints as $c )
        {
            if( property_exists( $c, 'htmlPattern' ) && !empty( $c->htmlPattern ) )
            {
                return [ $patternAttribute => $c->htmlPattern, 'data-message' => $c->message];
            }

            $patternAndMessage = $this->getPatternAndMessage( $field, $data );
            if( $data === true )
            {
                $patternAndMessage[$patternAttribute] = $patternAndMessage['pattern'];
                unset( $patternAndMessage['pattern'] );
            }
            else
            {
                $patternAndMessage[$patternAttribute] = anchorCheck( $patternAndMessage[$patternAttribute] );
            }
            $patternAndMessage['data-message'] = $patternAndMessage['message'];
            unset( $patternAndMessage['message'] );
            return $patternAndMessage;
        }
    }

    function anchorCheck( $patternAttribute )
    {
        $pa = $patternAttribute;
        // Remove the anchors for pattern attributes
        if( strpos( $pa, '^' ) === 0 )
        {
            $pa = substr( $pa, 1 );
        }
        if( strpos( $pa, '$' ) === strlen( $pa ) - 1 )
        {
            $pa = substr( $pa, -1 );
        }
        return $pa;
    }

}
