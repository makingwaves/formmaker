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
            ->add('name','text', array('label' => 'form.label.form.name'))

            ->add('firstPage', 'text', array('label' => 'form.label.first.page') )
            ->add('cssClass', 'text', array('label' => 'form.label.css.class',
                                            'required' => false
                                      ))
            ->add('summaryPage', 'checkbox', array('label' => 'form.label.want.confirmation',
                                                   'required' => false
                                             ))
            ->add('summaryLabel', 'text', array('label' => false,
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
                                                   'required' => false,
                                                   'attr'  => array(
                                                       'checked' => 'checked'
                                                   )
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
