<?php

namespace MakingWaves\FormMakerBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;

/**
 * Class ListController
 * @package MakingWaves\FormMakerBundle\Controller
 */
class ListController extends Controller
{
    /**
     * Action displays the list of form definitions stored in database
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function displayAction()
    {
        $formDefinitions = $this->getDoctrine()->getRepository('FormMakerBundle:FormDefinitions')->findAll();
        $userNames = array();

        // get users names
        foreach($formDefinitions as $definition) {

            $userId = $definition->getOwnerUser();
            if (isset($userNames[$userId])) {
                continue;
            }

            $userNames[$userId] = $this->getUserName($userId);
        }

        return $this->render(
            'FormMakerBundle:List:display.html.twig',
            array(
                'formDefinitions' => $formDefinitions,
                'userNames' => $userNames
            )
        );
    }

    /**
     * Method returns the user name
     * @param $userId
     * @return string
     */
    private function getUserName($userId)
    {
        $userService = $this->get('ezpublish.api.service.user');
        $user = $userService->loadUser($userId);

        return join(' ', array($user->getFieldValue('first_name'), $user->getFieldValue('last_name')));
    }
}
