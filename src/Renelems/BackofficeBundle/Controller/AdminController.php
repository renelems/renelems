<?php
namespace Renelems\BackofficeBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Doctrine\ORM\EntityManager;

use Renelems\BackofficeBundle\Manager\AdminManager;
use Renelems\DBBundle\Entity\Admin;
use Renelems\BackofficeBundle\Form\AdminType;

/**
 * Admin controller.
 *
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * Lists all Admin entities.
     *
     * @Route("/", name="admin_admin")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('RenelemsDBBundle:Admin')->findAll();
        
        return array(
            'entities' => $entities,
        );
    }


    /**
     * Displays a form to create a new Admin entity.
     *
     * @Route("/new", name="admin_admin_new")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Admin();
        $form   = $this->createForm(new AdminType($this->get('security.context')), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Admin entity.
     *
     * @Route("/create", name="admin_admin_create")
     * @Method("post")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("RenelemsBackofficeBundle:Admin:new.html.twig")
     */
    public function createAction()
    {
        $em = $this->getDoctrine()->getManager();
        $oLoggableListener = $this->get('stof_doctrine_extensions.listener.loggable');
        $oLoggableListener->setUsername($this->get('security.context')->getToken()->getUsername());
        $entity  = new Admin();
        $request = $this->getRequest();
        $form    = $this->createForm(new AdminType($this->get('security.context')), $entity);
        $form->bind($request);

        if ($form->isValid()) {

            /** @var AdminManager $oAdminManager  */
            $oAdminManager = $this->get('renelems.managers.admin');
            $entity = $oAdminManager->createAdmin($entity);
			
            $em->flush();
            
            return $this->redirect($this->generateUrl('admin_admin'));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Admin entity.
     *
     * @Route("/{id}/edit", name="admin_admin_edit")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        if(($this->get('security.context')->isGranted('ROLE_ADMIN') && $id != $user->getId()) || $id == $user->getId())
        {
            $entity = $em->getRepository('RenelemsDBBundle:Admin')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Admin entity.');
            }

            $editForm = $this->createForm(new AdminType($this->get('security.context')), $entity);
            $deleteForm = $this->createDeleteForm($id);

            return array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            );
        }
        else
        {
            throw new AccessDeniedException();
        }
    }

   
    /**
     * Edits an existing Admin entity.
     *
     * @Route("/{id}/update", name="admin_admin_update")
     * @Method("post")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("RenelemsBackofficeBundle:Admin:edit.html.twig")
     */
    public function updateAction($id)
    {
        $oLoggableListener = $this->get('stof_doctrine_extensions.listener.loggable');
        $oLoggableListener->setUsername($this->get('security.context')->getToken()->getUsername());

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RenelemsDBBundle:Admin')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Admin entity.');
        }

        if(($this->get('security.context')->isGranted('ROLE_ADMIN') && $id != $entity->getId()) || $id == $entity->getId()) {

            $editForm   = $this->createForm(new AdminType($this->get('security.context')), $entity);
            $deleteForm = $this->createDeleteForm($id);

            $request = $this->getRequest();

            $editForm->bind($request);

            if ($editForm->isValid()) {
                /** @var AdminManager $oAdminManager  */
                $oAdminManager = $this->get('renelems.managers.admin');
                $entity = $oAdminManager->updateAdmin($entity);

                $em->flush();
                
                $this->get('session')->getFlashBag()->add(
                	'notice',
                	'Wijzigingen opgeslagen!'
                );
            } else {
            	$this->get('session')->getFlashBag()->add(
            		'error',
            		'Wijzigingen niet opgeslagen!'
            	);
            }

            return array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            );
        }
        else
        {
            throw new AccessDeniedException();
        }
    }

    /**
     * Deletes a Admin entity.
     *
     * @Route("/{id}/delete", name="admin_admin_delete")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function deleteAction(Admin $entity)
    {
        $oLoggableListener = $this->get('stof_doctrine_extensions.listener.loggable');
        $oLoggableListener->setUsername($this->get('security.context')->getToken()->getUsername());
	
        if (!$entity) {
        	throw $this->createNotFoundException('Unable to find Admin entity.');
		}
		
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();
        
        $this->get('session')->getFlashBag()->add(
			'notice',
        	'Gebruiker is verwijderd!'
        );
		
        return $this->redirect($this->generateUrl('admin_admin'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

}
