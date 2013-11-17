<?php
namespace Renelems\BackofficeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectLogoType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file', array('label'=>'Logo', 'required' => false, 'constraints' => array(new Assert\File(array('maxSize' => '6000000', 'mimeTypes' => array('image/jpeg', 'image/png'))))))
            ;
        
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    	$resolver->setDefaults(array(
    		'data_class' => 'Renelems\DBBundle\Entity\ProjectImage',
    	));
    }

    public function getName()
    {
        return 'renelems_dbbundle_project_logo';
    }
}
