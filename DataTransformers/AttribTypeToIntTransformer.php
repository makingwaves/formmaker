<?php

namespace MakingWaves\FormMakerBundle\DataTransformers;

use Doctrine\Common\Persistence\ObjectManager;

class AttribTypeToIntTransformer extends EntityToIntTransformer
{
    public function __construct(ObjectManager $om)
    {
        parent::__construct($om);
        $this->setEntityClass('MakingWaves\FormMakerBundle\Entity\FormTypes');
        $this->setEntityRepo('FormMakerBundle:FormTypes');
        $this->setEntityType('FromType');
    } // __construct
} // class AttribTypeToIntTransformer