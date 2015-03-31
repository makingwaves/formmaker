<?php

namespace MakingWaves\FormMakerBundle\Form\FormAttributesDecorator;
use Symfony\Component\Form\Form;

interface FormAttributesDecoratorInterface
{
    public function decorate(Form $form);
}