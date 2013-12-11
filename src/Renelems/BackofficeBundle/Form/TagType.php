<?php
namespace Renelems\BackofficeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Validator\Constraints as Assert;


class TagType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array('label'=> false, 'required' => false, 'attr' => array('class' => 'tag_autocomplete')))
            ;
        
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    	$resolver->setDefaults(array(
    		'data_class' => 'Renelems\DBBundle\Entity\Tag',
    	));
    }

    public function getName()
    {
        return 'renelems_dbbundle_tag';
    }
}
