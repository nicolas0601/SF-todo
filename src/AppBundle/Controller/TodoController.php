<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Todo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * Todo controller.
 *
 */
class TodoController extends Controller
{
    /**
     * Lists all todo entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $todos = $em->getRepository('AppBundle:Todo')->findBy(['trashed' => 0], ['date' => 'DESC',

        ], 3);

        return $this->render('AppBundle:Todo:index.html.twig', array(
            'todos' => $todos,
        ));


    }


    /**
     * Creates a new todo entity.
     *
     */
    public function newAction(Request $request)
    {
        $todo = new Todo();
        $form = $this->createForm('AppBundle\Form\TodoType', $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush($todo);

            $this->addFlash('success', 'Le todo a bien été créé.');
            return $this->redirectToRoute('todo_show', array('id' => $todo->getId()));
        }

        return $this->render('AppBundle:Todo:new.html.twig', array(
            'todo' => $todo,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a todo entity.
     *
     */
    public function showAction(Todo $todo)
    {
        $deleteForm = $this->createDeleteForm($todo);

        $em = $this->getDoctrine()->getManager();

        $todos = $em->getRepository('AppBundle:Todo')->findBy(
            array('content' => $todo)
        );

        return $this->render('AppBundle:Todo:show.html.twig', array(
            'todo' => $todo,
            'todos' => $todos,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function listAllAction()
    {
        $em = $this->getDoctrine()->getManager();

        $todos = $em->getRepository('AppBundle:Todo')->findByTrashed();

        return $this->render('AppBundle:Todo:listAll.html.twig', array(
            'todos' => $todos,
        ));
    }


    /**
     * Displays a form to edit an existing todo entity.
     *
     */
    public function editAction(Request $request, Todo $todo)
    {
        $deleteForm = $this->createDeleteForm($todo);
        $editForm = $this->createForm('AppBundle\Form\TodoType', $todo);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Le todo a bien été édité.');
            return $this->redirectToRoute('todo_edit', array('id' => $todo->getId()));
        }

        return $this->render('AppBundle:Todo:edit.html.twig', array(
            'todo' => $todo,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a todo entity.
     *
     */
    public function deleteAction(Request $request, Todo $todo)
    {
        $form = $this->createDeleteForm($todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $em = $this->getDoctrine()->getManager();
                $em->remove($todo);
                $em->flush();

                $this->addFlash('success', 'Le todo a bien été supprimé.');
                return $this->redirectToRoute('todo_index');
            } catch (ForeignKeyConstraintViolationException $e) {
                $this->addFlash(
                    'danger',
                    'ce toto ne peut etre supprimé.'

                );
            }
        }

        return $this->redirectToRoute('todo_index');
    }

    /**
     * Creates a form to delete a todo entity.
     *
     * @param Todo $todo The todo entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Todo $todo)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('todo_delete', array('id' => $todo->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }


    public function trashedAction(Request $request)
    {
        $todo = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Todo')
            ->find($request->attributes->get('id'));

        $todo->setTrashed(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($todo);
        $em->flush();


        return $this->redirectToRoute('todo_index');


    }

    public function listTrashedAction(){

        $todos = $this->getDoctrine()
            ->getRepository('AppBundle:Todo')
            ->findBy(['trashed' => true],
                ['date' => 'DESC']
            );

        return $this->render('AppBundle:Todo:trashed.html.twig', [

            'todos' => $todos

        ]);

    }

    public function restoreAction(Request $request)
    {
        $todo = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Todo')
            ->find($request->attributes->get('id'));

        $todo->setTrashed(false);
        $em = $this->getDoctrine()->getManager();
        $em->persist($todo);
        $em->flush();


        return $this->redirectToRoute('todo_index');


    }



}
