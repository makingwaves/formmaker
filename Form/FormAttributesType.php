<?php

namespace MakingWaves\FormMakerBundle\Form;

use MakingWaves\FormMakerBundle\DataTransformers\AttribTypeToIntTransformer;
use MakingWaves\FormMakerBundle\Form\EventListener\AttribFieldsSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;

class FormAttributesType extends AbstractType
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var EventListener\AttribFieldsSubscriber
     */
    private $eventSubscriber;


    public function __construct(EntityManager $em, AttribFieldsSubscriber $afs)
    {
        $this->entityManager = $em;
        $this->eventSubscriber = $afs;
    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('enabled', 'checkbox', array( 'label' => 'label.enabled' ))
            ->add('label', 'text', array( 'label' => 'label.label',
                                          'required' => false ))
            ->add('attrOrder', 'integer', array( 'label' => 'label.attr.order',
                                                 'required' => false
            ))
            ->add('mandatory', 'checkbox', array('mapped' => false,
                                                 'required' => false,
                                                 'label' => 'label.mandatory'
            ))
            ->add('description', 'textarea', array( 'label' => 'label.description',
                                                    'required' => false
            ))
            ->add('css', 'text', array( 'label' => 'label.css',
                                        'required' => false
            ));
            $builder->add(
                $builder->create('type', 'hidden')
                    ->addModelTransformer(new AttribTypeToIntTransformer($this->entityManager))
            );

            $builder->addEventSubscriber($this->eventSubscriber);
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MakingWaves\FormMakerBundle\Entity\FormAttributes',
            'translation_domain' => 'form_attributes_type',

        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        /*
         * A placeholder '__TO_BE_REPLACED__ is added so it can be replaced by javascript when loading new attrib's form via ajax:
         * this form is always a part of FormDefinitions so the name we need is:
         *  - makingwaves_formmakerbundle_formdefinitions_[attributes][0] - but sf2 doesn't allow to create such names here,
         * we need to make them in js
         *
         */
        //return 'makingwaves_formmakerbundle_attributes';
        return 'makingwaves_formmakerbundle_formdefinitions__TO_BE_REPLACED__';
    } //
}
