<?php
namespace Renelems\WebsiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Validator\Constraints as Assert;

class ContactType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label'=>'Naam', 'required' => false, 'label_attr' => array('class' => 'arrow_box')))
            ->add('phone', 'number', array('label'=>'Telefoonnummer', 'required' => false, 'label_attr' => array('class' => 'arrow_box')))
            ->add('email', 'email', array('label'=>'E-mailadres', 'required' => true, 'label_attr' => array('class' => 'arrow_box'), 'constraints' => array(new Assert\NotBlank(array('message' => 'Verplicht veld')))))
            ->add('message', 'textarea', array('label'=>'Bericht', 'required' => true, 'attr' => array('rows' => '4'), 'label_attr' => array('class' => 'arrow_box'), 'constraints' => array(new Assert\NotBlank(array('message' => 'Verplicht veld')))))
        	;
    }

    public function getName()
    {
        return 'renelems_contact';
    }
}
