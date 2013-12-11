<?php
namespace Renelems\BackofficeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Validator\Constraints as Assert;

use Renelems\DBBundle\Entity\Admin;
use Renelems\BackofficeBundle\Form\Type\AutoCompleteType;
use Renelems\BackofficeBundle\Form\EventListener\TagFieldSubscriber;
use Renelems\DBBundle\Entity\Project;

class ProjectType extends AbstractType
{
    protected $project;

    public function __construct(Project $project)
    {
    	$this->project = $project;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array('label'=>'Titel', 'required' => true, 'constraints' => array(new Assert\NotBlank(array('message' => 'Verplicht veld')))))
            ->add('website', null, array('label'=>'Website', 'required' => true, 'constraints' => array(new Assert\NotBlank(array('message' => 'Verplicht veld')))))
            ->add('introduction', null, array('label'=>'Introductie', 'required' => true, 'constraints' => array(new Assert\NotBlank(array('message' => 'Verplicht veld')))))
            ->add('description', 'textarea', array('label'=>'Omschrijving', 'required' => true, 'constraints' => array(new Assert\NotBlank(array('message' => 'Verplicht veld'))), 'attr' => array('class' => 'ckeditor', 'cols' => '30')))
            ->add('tags', 'autocomplete', array('label'=>'Labels', 'required' => false, 'attr' => array('class' => 'tag_autocomplete', 'placeholder' => 'Begin met typen...')))
            ->add('active', null, array('label' => 'Actief', 'required' => false))
            ->add('images', 'collection', array('type' => new ProjectImageType(), 'mapped' => false, 'allow_add' => true))
            ->add('logo', 'file', array('label'=>'Logo', 'required' => false, 'data_class' => null, 'constraints' => array(new Assert\File(array('maxSize' => '6000000', 'mimeTypes' => array('image/jpeg', 'image/png'))))))
        	;
        
        
        $builder->addEventSubscriber(new TagFieldSubscriber());
    }
    
    public function preSetData(FormEvent $event)
    {
    	$data = $event->getData();
    	$form = $event->getForm();
    
    	// check if the product object is "new"
    	// If you didn't pass any data to the form, the data is "null".
    	// This should be considered a new "Product"
    	if (!$data || !$data->getId()) {
    		$form->add('name', 'text');
    	}
    }

    public function getName()
    {
        return 'renelems_dbbundle_project';
    }
}
