<?php

namespace MakingWaves\FormMakerBundle\Form;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class FormDefinitionsType
 * @package MakingWaves\FormMakerBundle\Form
 */
class FormDefinitionsType extends AbstractType
{
    /**
     * This setting comes from formmaker.yml
     * @var array
     */
    private $viewTypes;

    /**
     * @var \eZ\Publish\Core\Base\ServiceContainer
     */
    private $container;


    private $attributesType;

    /**
     * @param Container $container
     */
    public function __construct( Container $container, FormAttributesType $attribType)
    {
        $this->container = $container;
        $this->attributesType = $attribType;
        $this->prepareViewTypes();
    }

    /**
      * Prepares the viewType array which is ready to use in form
     */
    private function prepareViewTypes()
    {
        $viewTypes = $this->container->getParameter( 'formmaker.view_types' );
        $this->viewTypes = array_combine( $viewTypes, $viewTypes );
    }


    /**
     *
     * @return array
     */
    private function getViewTypes()
    {
        return $this->viewTypes;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text', array('label' => 'form.label.form.name'))

            ->add('firstPage', 'text', array('label' => 'form.label.first.page') )
            ->add('cssClass', 'text', array('label' => 'form.label.css.class',
                                            'required' => false
                                      ))
            ->add( 'viewType', 'choice', array(
                'label' => 'form.label.view.type',
                'multiple' => false,
                'choices' => $this->getViewTypes()
            ) )

            ->add('summaryPage', 'checkbox', array('label' => 'form.label.want.confirmation',
                                                   'required' => false
                                             ))
            ->add('summaryLabel', 'text', array('label' => 'form.label.confirmaton.label',
                                                'required' => false
                                          ))
            ->add('summaryBody', 'textarea', array('label' => 'form.label.confirmation.body',
                                                   'required' => false
                                            ))
            ->add('receiptLabel', 'text', array('label' => 'form.label.receipt.page.label'))
            ->add('receiptIntro', 'textarea', array('label' => 'form.label.receipt.page.text',
                                                    'required' => false
                                              ))
            ->add('receiptBody', 'textarea', array('label' => 'form.label.receipt.page.body',
                                                   'required' => false
                                            ))
            ->add('emailAction', 'checkbox', array('label' => 'form.label.send.data.via.email',
                                                   'required' => false
                                            ))
            ->add('emailTitle', 'text', array('label' => 'form.label.email.title',
                                              'required' => false,
                                        ))
            ->add('recipients', 'text', array('label' => 'form.label.email.recipients',
                                              'required' => false,
                                        ))

            ->add('storeAction', 'checkbox', array('label' => 'form.label.store.data.in.db',
                                                   'required' => false,
                                             ))
            ->add('objectAction', 'checkbox', array('label' => 'form.label.user.process.class',
                                                    'required' => false,
                                             ))
            ->add('processClass', 'text', array('label' => 'form.label.process.class.name',
                                                'required' => false,
                                          ));

            $builder->add('attributes', 'collection', array(
                                'type'      => $this->attributesType,
                                'allow_add' => true,
                                'prototype' => false
            ));


        $builder->add('save', 'submit');
    } // buildForm


    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MakingWaves\FormMakerBundle\Entity\FormDefinitions',
            'translation_domain' => 'form_definitions_type',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'makingwaves_formmakerbundle_formdefinitions';
    }
} // class FormDefinitionsType