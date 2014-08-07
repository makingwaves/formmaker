<?php

namespace MakingWaves\FormMakerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use MakingWaves\FormMakerBundle\DataTransformers\AttribTypeToIntTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TypeHiddenType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    private $om;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new AttribTypeToIntTransformer($this->om);
        $builder->addModelTransformer($transformer);
    } // buildForm


    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
           'invalid_message' => 'The selected issue does not exist'
        ));
    }


    public function getParent()
    {
        return 'hidden';
    }


    public function getName()
    {
        return 'type_hidden';
    }

} // class TypeHiddenType