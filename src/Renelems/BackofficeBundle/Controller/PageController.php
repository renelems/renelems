<?php
namespace Renelems\BackofficeBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Doctrine\ORM\EntityManager;

use Renelems\DBBundle\Entity\Page;
use Renelems\BackofficeBundle\Form\PageType;

/**
 * Admin controller.
 *
 * @Route("/page")
 */
class pageController extends Controller
{
    /**
     * Lists all Page entities.
     *
     * @Route("/", name="admin_page")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('RenelemsDBBundle:Page')->findAll();
        
        return array(
            'entities' => $entities,
        );
    }


    /**
     * Displays a form to create a new entity.
     *
     * @Route("/new", name="admin_page_new")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Page();
        $form   = $this->createForm(new PageType($entity), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new entity.
     *
     * @Route("/create", name="admin_page_create")
     * @Method("post")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("RenelemsBackofficeBundle:Page:new.html.twig")
     */
    public function createAction()
    {
        $em = $this->getDoctrine()->getManager();
        $oLoggableListener = $this->get('stof_doctrine_extensions.listener.loggable');
        $oLoggableListener->setUsername($this->get('security.context')->getToken()->getUsername());
        $entity  = new Page();
        $request = $this->getRequest();
        $form    = $this->createForm(new PageType($entity), $entity);
        $form->bind($request);
        
        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('admin_page_edit', array('id' => $entity->getId())));
        } else {
           	$this->get('session')->getFlashBag()->add(
        		'error',
         		'Opslaan mislukt!'
           	);
        }
		
        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing entity.
     *
     * @Route("/{id}/edit", name="admin_page_edit")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        if(($this->get('security.context')->isGranted('ROLE_ADMIN') && $id != $user->getId()) || $id == $user->getId())
        {
            $entity = $em->getRepository('RenelemsDBBundle:Page')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Page entity.');
            }
			
            $editForm = $this->createForm(new PageType($entity), $entity);
			$editForm->get('tags')->setData('');
            return array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
            );
        }
        else
        {
            throw new AccessDeniedException();
        }
    }

   
    /**
     * Edits an existing entity.
     *
     * @Route("/{id}/update", name="admin_page_update")
     * @Method("post")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("RenelemsBackofficeBundle:Page:edit.html.twig")
     */
    public function updateAction($id)
    {
        $oLoggableListener = $this->get('stof_doctrine_extensions.listener.loggable');
        $oLoggableListener->setUsername($this->get('security.context')->getToken()->getUsername());

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RenelemsDBBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        if(($this->get('security.context')->isGranted('ROLE_ADMIN') && $id != $entity->getId()) || $id == $entity->getId()) {
            $editForm   = $this->createForm(new PageType($entity), $entity);
            $request = $this->getRequest();
            $editForm->bind($request);
            
            if ($editForm->isValid()) {
            	
                $em->persist($entity);
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

        }
        else
        {
            throw new AccessDeniedException();
        }
        return $this->redirect($this->generateUrl('admin_page_edit', array('id' => $id)));
    }

    /**
     * Deletes a entity.
     *
     * @Route("/{id}/delete", name="admin_page_delete")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function deleteAction(Page $entity)
    {
        $oLoggableListener = $this->get('stof_doctrine_extensions.listener.loggable');
        $oLoggableListener->setUsername($this->get('security.context')->getToken()->getUsername());
	
        if (!$entity) {
        	throw $this->createNotFoundException('Unable to find Page entity.');
		}
		
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();
        
        $this->get('session')->getFlashBag()->add(
			'notice',
        	'Pagina is verwijderd!'
        );
		
        return $this->redirect($this->generateUrl('admin_page'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

}
