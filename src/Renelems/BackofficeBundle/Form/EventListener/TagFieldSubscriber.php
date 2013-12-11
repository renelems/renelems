<?php
// src/Acme/DemoBundle/Form/EventListener/AddNameFieldSubscriber.php
namespace Renelems\BackofficeBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Renelems\DBBundle\Entity\Project;

class TagFieldSubscriber implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
		// Tells the dispatcher that you want to listen on the form.pre_set_data
		// event and that the preSetData method should be called.
		return array(
				FormEvents::POST_BIND => 'emptyTag'
		);
	}

	public function emptyTag(FormEvent $event)
	{
		$data = $event->getData();
		$form = $event->getForm();

		$data->setTags(NULL);
	}
}