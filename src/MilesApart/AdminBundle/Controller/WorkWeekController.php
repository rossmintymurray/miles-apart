<?php

namespace MilesApart\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use MilesApart\AdminBundle\Entity\WorkWeek;
use MilesApart\AdminBundle\Form\WorkWeekType;

/**
 * WorkWeek controller.
 *
 */
class WorkWeekController extends Controller
{

    /**
     * Lists all WorkWeek entities.
     *
     */
    public function indexAction($page=null)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("HR", $this->get("router")->generate("miles_apart_admin_hr"));
        $breadcrumbs->addItem("Work Week", $this->get("router")->generate("hr_work-week"));

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MilesApartAdminBundle:WorkWeek')->findAll();

        //Set up pagerfanta
        $adapter = new ArrayAdapter($entities);
        
        //Pass adapter to pagerfanta
        $pager =  new Pagerfanta($adapter);
        //Set the number of results
        $pager->setMaxPerPage(10);

        //Set current page if not set
        if (!$page)    
            $page = 1;
            try  {
                $pager->setCurrentPage($page);
            }
            catch(NotValidCurrentPageException $e) {
              throw new NotFoundHttpException('Illegal page');
            }

        return $this->render('MilesApartAdminBundle:WorkWeek:index.html.twig', array(
            'pager' => $pager,
        ));
    }
    /**
     * Creates a new WorkWeek entity.
     *
     */
    public function createAction(Request $request)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("HR", $this->get("router")->generate("miles_apart_admin_hr"));
        $breadcrumbs->addItem("Work Week", $this->get("router")->generate("hr_work-week"));
        $breadcrumbs->addItem("New", $this->get("router")->generate("hr_work-week_new"));

        $entity = new WorkWeek();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'New work week has been created successfully.');

            return $this->redirect($this->generateUrl('hr_work-week_show', array('id' => $entity->getId())));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartAdminBundle:WorkWeek:new.html.twig', array(
            'submitted' => $submitted,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a WorkWeek entity.
    *
    * @param WorkWeek $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(WorkWeek $entity)
    {
        $form = $this->createForm(new WorkWeekType(), $entity, array(
            'action' => $this->generateUrl('hr_work-week_create'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));


        return $form;
    }

    /**
     * Displays a form to create a new WorkWeek entity.
     *
     */
    public function newAction()
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("HR", $this->get("router")->generate("miles_apart_admin_hr"));
        $breadcrumbs->addItem("Work Week", $this->get("router")->generate("hr_work-week"));
        $breadcrumbs->addItem("New", $this->get("router")->generate("hr_work-week_new"));

        $entity = new WorkWeek();
        $form   = $this->createCreateForm($entity);

        return $this->render('MilesApartAdminBundle:WorkWeek:new.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a WorkWeek entity.
     *
     */
    public function showAction($id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("HR", $this->get("router")->generate("miles_apart_admin_hr"));
        $breadcrumbs->addItem("Work Week", $this->get("router")->generate("hr_work-week"));
        $breadcrumbs->addItem("Show", $this->get("router")->generate("hr_work-week_show", array ('id'=> $id )));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:WorkWeek')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find WorkWeek entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MilesApartAdminBundle:WorkWeek:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing WorkWeek entity.
     *
     */
    public function editAction($id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("HR", $this->get("router")->generate("miles_apart_admin_hr"));
        $breadcrumbs->addItem("Work Week", $this->get("router")->generate("hr_work-week"));
        $breadcrumbs->addItem("Edit", $this->get("router")->generate("hr_work-week_edit", array ('id'=> $id )));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:WorkWeek')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find WorkWeek entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MilesApartAdminBundle:WorkWeek:edit.html.twig', array(
            'submitted' => false,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a WorkWeek entity.
    *
    * @param WorkWeek $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(WorkWeek $entity)
    {
        $form = $this->createForm(new WorkWeekType(), $entity, array(
            'action' => $this->generateUrl('hr_work-week_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }
    /**
     * Edits an existing WorkWeek entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("HR", $this->get("router")->generate("miles_apart_admin_hr"));
        $breadcrumbs->addItem("Work Week", $this->get("router")->generate("hr_work-week"));
        $breadcrumbs->addItem("Edit", $this->get("router")->generate("hr_work-week_edit", array ('id'=> $id )));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:WorkWeek')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find WorkWeek entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The work week was updated successfully.');

            return $this->redirect($this->generateUrl('hr_work-week_edit', array('id' => $id)));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartAdminBundle:WorkWeek:edit.html.twig', array(
            'submitted' => $submitted,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a WorkWeek entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MilesApartAdminBundle:WorkWeek')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find WorkWeek entity.');
            }

            $em->remove($entity);
            $em->flush();
            
            //Set the success flashbag
            $this->get('session')->getFlashBag()->set('admin-notice', 'The work week was deleted successfully.');
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem deleting the work week, please check the details below.');
        }

        return $this->redirect($this->generateUrl('hr_work-week'));
    }

    /**
     * Creates a form to delete a WorkWeek entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('hr_work-week_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array(
                        'class' => 'btn-xs btn-danger col-md-2')))
            ->getForm()
        ;
    }
}
