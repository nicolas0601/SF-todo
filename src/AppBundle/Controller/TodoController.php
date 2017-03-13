<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TodoController
 * @package AppBundle\Controller
 */
class TodoController extends Controller
{
    /**
     * Index action.
     */
    public function indexAction()
    {
        $form = $this
            ->createFormBuilder()
            ->add('content', 'textarea', [
                'label' => false,
            ])
            ->getForm();

        return $this->render('AppBundle:Todo:index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
