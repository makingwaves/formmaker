<?php

namespace MakingWaves\FormMakerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FormAttributesOptionsType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', 'text', array('label' => 'label.label'))
            ->add('optOrder', 'hidden')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MakingWaves\FormMakerBundle\Entity\FormAttributesOptions',
            'translation_domain' => 'form_attributes_type'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'makingwaves_formmakerbundle_formattributesoptions';
    }
}
