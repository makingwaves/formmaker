<?php

namespace MakingWaves\FormMakerBundle\DataTransformers;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EntityToIntTransformer implements DataTransformerInterface
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $entity_manager;

    private $entity_class;

    private $entity_type;

    private $entity_repo;

    /**
     * @param ObjectManager $em
     */
    public function __construct(ObjectManager $em)
    {
        $this->entity_manager = $em;
    }


    /**
     * @param mixed $entity
     * @return mixed
     * @throws \Symfony\Component\Form\Exception\TransformationFailedException
     */
    public function transform($entity)
    {
        if ( $entity === null || !($entity instanceof $this->entity_class )) {
            
            return null;
        }

        return $entity->getId();
    } // transform


    /**
     * @param mixed $id
     * @return mixed|object
     * @throws \Symfony\Component\Form\Exception\TransformationFailedException
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            throw new TransformationFailedException('No '.$this->entity_type.' id was submitted');
        }

        $entity = $this->entity_manager->getRepository($this->entity_repo)->findOneBy(array('id' => $id));

        if ( $entity === null ) {
            throw new TransformationFailedException('A '.$this->entity_type.' with id '.$id.' was not found');
        }

        return $entity;
    } // reverseTransform


    /**
     * @param mixed $entity_class
     */
    public function setEntityClass($entity_class)
    {
        $this->entity_class = $entity_class;
    }

    /**
     * @param mixed $entity_type
     */
    public function setEntityType($entity_type)
    {
        $this->entity_type = $entity_type;
    }

    /**
     * @param mixed $entity_repo
     */
    public function setEntityRepo($entity_repo)
    {
        $this->entity_repo = $entity_repo;
    }

} // class EntityToIntTransformer