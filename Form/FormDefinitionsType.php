<?php

namespace MakingWaves\FormMakerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FormDefinitionsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text', array('label' => 'Form name'))
            ->add('firstPage', 'text', array('label' => 'First page label') )
            ->add('cssClass', 'text', array('label' => 'Form CSS class',
                                            'required' => false
                                      ))
            ->add('summaryPage', 'checkbox', array('label' => 'I want a confirmation page with the following label',
                                                   'required' => false
                                             ))
            ->add('summaryLabel', 'text', array('label' => false,
                                                'required' => false
                                          ))
            ->add('summaryBody', 'textarea', array('label' => 'Confirmation page body text',
                                                   'required' => false,
                                                    'attr' => array(
                                                        'placeholder' => 'You\'re about to send following information. Are they OK?'
                                                    )
                                            ))
            ->add('receiptLabel', 'text', array('label' => 'Receipt page label'))
            ->add('receiptIntro', 'textarea', array('label' => 'Receipt page intro text',
                                                    'required' => false
                                              ))
            ->add('receiptBody', 'textarea', array('label' => 'Receipt page body text',
                                                   'required' => false,
                                                   'attr'  => array(
                                                        'placeholder' => 'Thank you for sending us the information!'
                                                    )
                                            ))
            ->add('emailAction', 'checkbox', array('label' => 'Send data via email',
                                                   'required' => false,
                                                   'attr'  => array(
                                                       'checked' => 'checked'
                                                   )
                                            ))
            ->add('emailTitle', 'text', array('label' => 'E-mail title',
                                              'required' => false,
                                              'attr'  => array(
                                                  'placeholder' => 'New form answer'
                                              )
                                        ))
            ->add('recipients', 'text', array('label' => 'E-mail recipients (separated by semicolon)',
                                              'required' => false,
                                        ))

            ->add('storeAction', 'checkbox', array('label' => 'Store data in database',
                                                   'required' => false,
                                             ))
            ->add('objectAction', 'checkbox', array('label' => 'Use process class method (advanced)',
                                                    'required' => false,
                                             ))
            ->add('processClass', 'text', array('label' => 'Process class name',
                                                'required' => false,
                                          ))
            ->add('save', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MakingWaves\FormMakerBundle\Entity\FormDefinitions'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'makingwaves_formmakerbundle_formdefinitions';
    }
}
