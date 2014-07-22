<?php

namespace MakingWaves\FormMakerBundle\Form;

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
        $builder
            ->add('attrOrder', 'integer', array( 'label' => 'label.attr.order',
                                                 'required' => false
            ))
            ->add('identifier', 'text', array( 'label' => 'label.identifier',
                                               'required' => false
            ))
            ->add('defaultValue', 'text', array( 'label' => 'label.default.value',
                                                 'required' => false
            ))
            ->add('enabled', 'checkbox', array( 'label' => 'label.enabled' ))
            ->add('label', 'text', array( 'label' => 'label.label',
                                          'required' => false
            ))
            ->add('emailReceiver', 'integer', array( 'label' => 'label.email.receiver'))
            ->add('description', 'textarea', array( 'label' => 'label.description'))
            ->add('css', 'text', array( 'label' => 'label.css'))
            ->add('allowedFileTypes', 'text', array( 'label' => 'label.allowed.file.types'))
            ->add('regex', 'text', array( 'label' => 'label.regex',
                                          'required' => false
            ))
            //->add('type', 'entity')
            ->add('validators')
        ;
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
