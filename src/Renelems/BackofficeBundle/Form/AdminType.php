<?php
namespace Renelems\BackofficeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

use Renelems\DBBundle\Entity\Admin;

class AdminType extends AbstractType
{
    const ADMIN_TYPE_ADMIN = 'admin';
    
    protected $securityContext;

    public function __construct(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $aAdminTypes = array(
            self::ADMIN_TYPE_ADMIN => 'Beheerder',
        );

        $builder->add('type', 'choice', array( 'label' => 'Niveau', 'choices' => $aAdminTypes, 'constraints' => array(new Assert\NotBlank(array('message' => 'Verplicht veld')))));
        
        $builder
            ->add('name', null, array('label'=>'Naam', 'required' => true, 'constraints' => array(new Assert\NotBlank(array('message' => 'Verplicht veld')))))
            ->add('email', null, array('label'=>'E-mailadres', 'required' => true, 'constraints' => array(new Assert\NotBlank(array('message' => 'Verplicht veld')), new Assert\Email(array(
		        	'message' => 'Ongeldig e-mailadres',
		            'checkMX' => true
	        )))))
            ->add('newpassword', 'repeated', array(
            		'type' => 'password',
            		'required' => true,
            		'invalid_message' => 'De wachtwoord velden komen niet overeen',
            		'constraints' => array(new Assert\NotBlank(array('message' => 'Verplicht veld'))),
            		'first_options'  => array('label' => 'Wachtwoord'),
            		'second_options' => array('label' => 'Wachtwoord herhalen'),
            ))
            ->add('active', null, array('label' => 'Actief', 'required' => false))
            ->add('roles', 'choice', array('label' => 'Rechten', 'choices' => Admin::$aRoles, 'multiple' => true, 'expanded' => true));
        
    }

    public function getName()
    {
        return 'renelems_dbbundle_admin';
    }
}
