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

class PageType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array('label'=>'Titel', 'required' => true, 'constraints' => array(new Assert\NotBlank(array('message' => 'Verplicht veld')))))
            ->add('introduction', 'textarea', array('label'=>'Introductie', 'required' => true, 'constraints' => array(new Assert\NotBlank(array('message' => 'Verplicht veld')))))
            ->add('content', 'textarea', array('label'=>'Inhoud', 'required' => true, 'constraints' => array(new Assert\NotBlank(array('message' => 'Verplicht veld'))), 'attr' => array('class' => 'ckeditor', 'cols' => '30')))
            ->add('isOnHomepage', 'checkbox', array('label'=>'Op homepage laten zien', 'required' => false))
        	;
    }

    public function getName()
    {
        return 'renelems_dbbundle_page';
    }
}
