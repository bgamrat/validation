<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of DefaultController
 *
 * @author bgamrat
 */
class DefaultController extends Controller
{

    public function index( Request $request )
    {
        $formMap = ['constraint' => 'App\Form\ContactConstraintType',
            'yaml' => 'App\Form\ContactYamlType'];

        $validate = $request->get( 'novalidate' ) !== null ?
                ['attr' => ['novalidate' => 'novalidate']] :
                [];

        $forms = $formViews = [];
        foreach( $formMap as $name => $type )
        {
            $formName = $name . '_form';
            $forms[$formName] = $this->createForm( $type, null, $validate );

            $forms[$formName]->handleRequest( $request );
            if( $forms[$formName]->isSubmitted() && $forms[$formName]->isValid() )
            {
                $contact = $forms[$formName]->getData();
            }
            $formViews[$formName] = $forms[$formName]->createView();
        }

        return $this->render( 'default/contact.html.twig', [
                    'forms' => $formViews] );
    }

    public function anno( Request $request )
    {
        $form = $this->createForm( 'App\Form\ContactAnnotationType', null );
        $form->handleRequest( $request );
        if( $form->isSubmitted() && $form->isValid() )
        {
            $contact = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist( $contact );
            $entityManager->flush();

            $this->addFlash(
                    'notice', 'Your message was saved!'
            );
        }
        return $this->render( 'default/anno.html.twig', [
                    'form' => $form->createView()] );
    }

    public function show( Request $request )
    {
        $repository = $this->getDoctrine()->getRepository( 'App\Entity\ContactAnnotation' );
        $contacts = $repository->findAll();

        return $this->render( 'default/show.html.twig', [
                    'contacts' => $contacts] );
    }

}
