<?php

namespace MakingWaves\FormMakerBundle\Services;

use \eZ\Publish\API\Repository\Values\User\User as eZUser;

/**
 * Class User
 * @package MakingWaves\FormMakerBundle\Services
 */
class User
{
    /**
     * @var \eZ\Publish\Core\Repository\UserService
     */
    private $userService;

    /**
     * @var \eZ\Publish\Core\Repository\Values\User\User
     */
    private $user;


    /**
     * @param \eZ\Publish\Core\Repository\UserService $userService
     */
    public function __construct( \eZ\Publish\Core\Repository\UserService $userService )
    {
        $this->userService = $userService;
    }

    /**
     * @param $userId
     */
    public function loadUser( $userId )
    {
        $this->user = $this->userService->loadUser( $userId );
    }

    /**
     * Returns the user name
     * @return string
     * @throws \Exception
     */
    public function getName()
    {
        if ( !( $this->user instanceof eZUser ) ) {
            throw new \Exception( 'User object property is not set.' );
        }

        return join( ' ', array( $this->user->getFieldValue( 'first_name' ), $this->user->getFieldValue( 'last_name' ) ) );
    }
} 