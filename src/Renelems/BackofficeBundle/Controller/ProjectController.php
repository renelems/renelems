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

use Renelems\DBBundle\Entity\Project;
use Renelems\DBBundle\Entity\ProjectImage;
use Renelems\BackofficeBundle\Form\ProjectType;

/**
 * Admin controller.
 *
 * @Route("/project")
 */
class ProjectController extends Controller
{
    /**
     * Lists all Project entities.
     *
     * @Route("/", name="admin_project")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('RenelemsDBBundle:Project')->findAll();
        
        return array(
            'entities' => $entities,
        );
    }


    /**
     * Displays a form to create a new Project entity.
     *
     * @Route("/new", name="admin_project_new")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Project();
        $form   = $this->createForm(new ProjectType($this->get('security.context')), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Project entity.
     *
     * @Route("/create", name="admin_project_create")
     * @Method("post")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("RenelemsBackofficeBundle:Project:new.html.twig")
     */
    public function createAction()
    {
        $em = $this->getDoctrine()->getManager();
        $oLoggableListener = $this->get('stof_doctrine_extensions.listener.loggable');
        $oLoggableListener->setUsername($this->get('security.context')->getToken()->getUsername());
        $entity  = new Project();
        $request = $this->getRequest();
        $form    = $this->createForm(new ProjectType($this->get('security.context')), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();
            
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
     * Displays a form to edit an existing Project entity.
     *
     * @Route("/{id}/edit", name="admin_project_edit")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        if(($this->get('security.context')->isGranted('ROLE_ADMIN') && $id != $user->getId()) || $id == $user->getId())
        {
            $entity = $em->getRepository('RenelemsDBBundle:Project')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Project entity.');
            }
			
            $editForm = $this->createForm(new ProjectType($this->get('security.context')), $entity);
			
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
     * Edits an existing Project entity.
     *
     * @Route("/{id}/update", name="admin_project_update")
     * @Method("post")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("RenelemsBackofficeBundle:Project:edit.html.twig")
     */
    public function updateAction($id)
    {
        $oLoggableListener = $this->get('stof_doctrine_extensions.listener.loggable');
        $oLoggableListener->setUsername($this->get('security.context')->getToken()->getUsername());

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RenelemsDBBundle:Project')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        if(($this->get('security.context')->isGranted('ROLE_ADMIN') && $id != $entity->getId()) || $id == $entity->getId()) {

            $editForm   = $this->createForm(new ProjectType($this->get('security.context')), $entity);

            $request = $this->getRequest();
			
            foreach($request->files->get('renelems_dbbundle_project') as $files) {
            	$files = $files[0]['file'];
            }
            $request->files->set('renelems_dbbundle_project', array('images' => array(NULL)));
            $editForm->bind($request);
            
            if ($editForm->isValid()) {
            	
            	foreach($files as $file) {
            		$oImage = new ProjectImage();
            		$oImage->setFile($file);
            		$oImage->setType('overview');
            		$oImage->setProject($entity);
            		$entity->addImage($oImage);
            	}
                //$em->persist($entity);
                //$em->flush();
                
                //$images = $entity->getImages();
                
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
     * Deletes a Project entity.
     *
     * @Route("/{id}/delete", name="admin_project_delete")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function deleteAction(Project $entity)
    {
        $oLoggableListener = $this->get('stof_doctrine_extensions.listener.loggable');
        $oLoggableListener->setUsername($this->get('security.context')->getToken()->getUsername());
	
        if (!$entity) {
        	throw $this->createNotFoundException('Unable to find Project entity.');
		}
		
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();
        
        $this->get('session')->getFlashBag()->add(
			'notice',
        	'Project is verwijderd!'
        );
		
        return $this->redirect($this->generateUrl('admin_project'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

}
