<?php
namespace Renelems\BackofficeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

use Renelems\DBBundle\Entity\Admin;

class ProjectType extends AbstractType
{
    protected $securityContext;

    public function __construct(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array('label'=>'Titel', 'required' => true, 'constraints' => array(new Assert\NotBlank(array('message' => 'Verplicht veld')))))
            ->add('website', null, array('label'=>'Website', 'required' => true, 'constraints' => array(new Assert\NotBlank(array('message' => 'Verplicht veld')))))
            ->add('introduction', null, array('label'=>'Introductie', 'required' => true, 'constraints' => array(new Assert\NotBlank(array('message' => 'Verplicht veld')))))
            ->add('description', 'textarea', array('label'=>'Omschrijving', 'required' => true, 'constraints' => array(new Assert\NotBlank(array('message' => 'Verplicht veld')))))
            ->add('tags', null, array('label'=>'Labels', 'required' => true, 'constraints' => array(new Assert\NotBlank(array('message' => 'Verplicht veld')))))
            ->add('active', null, array('label' => 'Actief', 'required' => false))
            ->add('images', 'collection', array('type' => new ProjectImageType(), 'mapped' => false, 'allow_add' => true))
            //->add('images', 'file', array('label'=>'Afbeeldingen', 'required' => false, 'data_class' => null, 'attr' => array('multiple' => 'multiple'), 'constraints' => array(new Assert\File(array('maxSize' => '6000000', 'mimeTypes' => array('image/jpeg', 'image/png'))))))
        	;
        
    }

    public function getName()
    {
        return 'renelems_dbbundle_project';
    }
}
