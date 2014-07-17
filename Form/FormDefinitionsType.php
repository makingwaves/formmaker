<?php

namespace MakingWaves\FormMakerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
            ->add('save', 'submit');

        //$builder->addEventListener(FormEvents::POST_SUBMIT, array($this, 'onPostSubmit'));
    } // buildForml


    public function onPostSubmit( FormEvent $event )
    {
        $form = $event->getForm();
        // if checkbox "i want confirmation page.. " checked
        if ( $form->getViewData()->getSummaryPage() ) {
            if ( $form->getViewData()->getSummaryLabel() == "") {
                $form->get('summaryLabel')->addError(new FormError('form.conf.page.label'));

            }
            if ( $form->getViewData()->getSummaryBody() == "") {
                $form->get('summaryBody')->addError(new FormError('form.conf.page.body'));
            }
        } // endif

        if ( $form->getViewData()->getEmailAction() ) {
            if ( $form->getViewData()->getEmailTitle() == "") {
                $form->get('emailTitle')->addError(new FormError('form.data.email'));
            }
            if ( $form->getViewData()->getRecipients() == "") {
                $form->get('recipients')->addError(new FormError('form.email.recipients'));
            }
        } // endif

    } // onPreSubmit


    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MakingWaves\FormMakerBundle\Entity\FormDefinitions',
            'translation_domain' => 'form_definitions_type'
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
