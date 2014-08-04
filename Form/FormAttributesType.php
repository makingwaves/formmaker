<?php

namespace MakingWaves\FormMakerBundle\Form;

use MakingWaves\FormMakerBundle\Form\EventListener\AttribFieldsSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FormAttributesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new AttribFieldsSubscriber());

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
            ))
            // end of common fields
            ;
            /*
            ->add('identifier', 'text', array( 'label' => 'label.identifier',l
                                               'required' => false
            ))
            ->add('defaultValue', 'text', array( 'label' => 'label.default.value',
                                                 'required' => false
            ))

            ->add('emailReceiver', 'integer', array( 'label' => 'label.email.receiver'))


            ->add('allowedFileTypes', 'text', array( 'label' => 'label.allowed.file.types'))
            ->add('regex', 'text', array( 'label' => 'label.regex',
                                          'required' => false
            ))
            ->add('validators')
        ;*/
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MakingWaves\FormMakerBundle\Entity\FormAttributes',
            'translation_domain' => 'form_attributes_type'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'makingwaves_formmakerbundle_formattributes';
    }
}
