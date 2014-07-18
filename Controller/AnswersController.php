<?php

namespace MakingWaves\FormMakerBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;


class AnswersController extends Controller
{
    /**
     * Indicate how many answers should be displayed on one page
     */
    CONST ITEMS_PER_PAGE = 5;

    public function displayAction()
    {
        $repository = $this->getDoctrine()->getRepository( 'FormMakerBundle:FormAnswers' );
        $userNames = array();
        $userService = $this->get( 'formmaker.user' );

        $query = $repository->createQueryBuilder( 'fa' )
            ->orderBy( 'fa.answerDate', 'DESC' )
            ->setFirstResult( 0 )
            ->setMaxResults( self::ITEMS_PER_PAGE )
            ->getQuery();

        $results = $query->getResult();

        // get users names
        foreach( $results as $answer ) {

            $userId = $answer->getUserId();
            if ( isset( $userNames[$userId] ) ) {
                continue;
            }

            $userService->loadUser( $userId );
            $userNames[$userId] = $userService->getName();
        }

        return $this->render(
            'FormMakerBundle:Answers:display.html.twig',
            array(
                'results' => $results,
                'userNames' => $userNames
            )
        );
    }
}