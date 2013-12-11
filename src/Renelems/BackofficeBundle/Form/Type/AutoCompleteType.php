<?php
namespace Renelems\BackofficeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class AutoCompleteType extends AbstractType
{
	public function getParent()
	{
		return 'text';
	}

	public function getName()
	{
		return 'autocomplete';
	}
}