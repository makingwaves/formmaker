<?php

namespace MakingWaves\FormMakerBundle\Form\EventListener;

use MakingWaves\FormMakerBundle\Entity\FormAttributes;
use MakingWaves\FormMakerBundle\Form\FormAttributesDecorator\FormAttributesDecoratorInterface;
use Symfony\Component\DependencyInjection\Container;
use MakingWaves\FormMakerBundle\Entity\FormTypes;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AttribFieldsSubscriber implements EventSubscriberInterface
{

    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    private $container;

    private $typesConfig = array();

    public function __construct(Container $serviceContainer)
    {
        $this->container = $serviceContainer;
        $this->typesConfig = $this->container->getParameter( 'formmaker.attributes.fields' );
    }

    /**
     * Attaches listeners to form events
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(FormEvents::POST_SET_DATA => 'postSetData');
    }


    /**
     * @param FormEvent $event
     * @return bool
     */
    public function postSetData(FormEvent $event)
    {

        $form = $event->getForm();
        /**
         * @var FormAttributes $attribute
         */
        $attribute = $form->getData(); // form's entity
        if ( ! $attribute instanceof FormAttributes ) {

            return false;
        }
        $type = $attribute->getType();
        if ( ! $type instanceof FormTypes) {

            return false;
        }

        $config = $this->getConfigForType( $type->getStringId() );
        if ( $config == null ) {

            return false;
        }

        foreach ($config as $field) {
            $objDecorator = $this->getDecoratorForField($field);
            if ( ! $objDecorator instanceof FormAttributesDecoratorInterface ) {
                continue;
            }
            $objDecorator->decorate($form);
        }

    } // preSetData


    private function getDecoratorForField($fieldName)
    {
        $class = 'MakingWaves\FormMakerBundle\Form\FormAttributesDecorator\\' . $this->container->camelize($fieldName) . 'Decorator';

        return new $class;
    }


    private function getConfigForType($strType)
    {
        if ( isset($this->typesConfig[$strType]) ) {

            return $this->typesConfig[$strType];
        }

        return null;
    }

    /**
     * Adds text field for default value
     *
     * @param $form
     */
    protected function addDefaultValueTextField($form)
    {
        $form->add('defaultValue', 'text', array('label' => 'label.identifier',
                                                 'required' => false
        ));
    } // addDefaultValueText


    /**
     * Adds checkbox field for defualt value
     *
     * @param $form
     */
    protected function addDefaultValueCheckboxField($form)
    {
        $form->add('defaultValue', 'checkbox', array(
                                                'label' => 'label.identifier',
                                                'required' => false
        ));
    } // addDefaultValueCheckbox


    /**
     * Add select field with validators
     *
     * @param $form
     */
    protected function addValidationField($form)
    {
        $form->add('validators', 'entity', array(
                                               'class'  => 'FormMakerBundle:FormValidators',
                                               'property' => 'description',
                                               'multiple' => false
        ));
    } // addValidationField


    protected function addOptionsField($form)
    {
        $form->add('options', 'collection', array(
                                             'type' => new FormAttributesOptionsType()
        ));
    } // addValidationField

} // class
