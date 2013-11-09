<?php
namespace Renelems\BackofficeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Security\Core\SecurityContextInterface;

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

            $builder->add('type', 'choice', array( 'label' => 'Niveau', 'choices' => $aAdminTypes));
        
        $builder
            ->add('name', null, array('label'=>'Naam'))
            ->add('email', null, array('label'=>'E-mailadres'))
            ->add('newpassword', 'password', array( 'label' => 'Wachtwoord (laat veld leeg als je deze niet wilt wijzigen)', 'required' => false))
            ->add('active', null, array('label' => 'Actief', 'required' => false))
            ->add('roles', 'choice', array('label' => 'Rechten', 'choices' => Admin::$aRoles, 'multiple' => true, 'expanded' => true));
        
    }

    public function getName()
    {
        return 'renelems_dbbundle_admin';
    }
}
