<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 7/29/14
 * Time: 2:46 PM
 */

namespace MakingWaves\FormMakerBundle\Form\EventListener;

use MakingWaves\FormMakerBundle\Entity\FormAttributesOptions;
use MakingWaves\FormMakerBundle\Entity\FormTypes;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AttribFieldsSubscriber implements EventSubscriberInterface
{
    /**
     * Attaches listeners to form events
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    } // getSubscribedEvents


    /**
     * PRE_SET_DATA - kind of before rendering the form, during building it actually
     *
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        /**
         * @var FormAttributes $attribute
         */
        $attribute = $event->getData(); // form's entity
        if ( $attribute == null ) {

            return false;
        }

        $strType = $attribute->getType()->getStringId();
        $form = $event->getForm();
        // we add diferent fields to the form, depending on attribute type
        switch($strType) {
            case FormTypes::CHECKBOX_ID:
                break;
            case FormTypes::TEXTLINE_ID:
//                $this->addDefaultValueTextField($form);
//                $this->addValidationField($form);
                break;
            case FormTypes::RADIO_ID:
            case FormTypes::SELECT_ID:
                //$this->addOptionsField($form);
                break;
        } // endswitch
    } // preSetData


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
