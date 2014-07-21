<?php

namespace MakingWaves\FormMakerBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use MakingWaves\FormMakerBundle\Entity\FormTypes;


class AnswersController extends Controller
{
    /**
     * Indicate how many answers should be displayed on one page
     * @var int
     */
    CONST ITEMS_PER_PAGE = 5;

    /**
     * Allowed length of summary text
     * @var int
     */
    CONST SUMMARY_STRING_LENGTH = 120;

    /**
     * @var array
     */
    private $summaryAttributes = array( FormTypes::TEXTLINE_ID, FormTypes::SELECT_ID, FormTypes::RADIO_ID, FormTypes::CHECKBOX_ID );

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
        $summaries = array();

        // get users names
        foreach( $results as $answer ) {

            $userId = $answer->getUserId();
            $summaries[$answer->getId()] = $this->getAnswerSummary( $answer->getId() );

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
                'userNames' => $userNames,
                'summaries' => $summaries
            )
        );
    }

    /**
     * Generate a quick summary of the answer. The result is used on answer list page.
     * @param int
     * @return string
     */
    private function getAnswerSummary( $answerId )
    {
        $output = '';
        $separator = ' / ';
        $answerAttributes = $this->getDoctrine()->getRepository( 'FormMakerBundle:FormAnswersAttributes' )->findBy( array(
            'answerId' => $answerId
        ) );

        foreach( $answerAttributes as $answerField ) {

            $attribute = $answerField->getAttribute();
            if ( !in_array( $attribute->getType()->getStringId(), $this->summaryAttributes ) ) {
                continue;
            }

            if ( strlen( $output ) >= self::SUMMARY_STRING_LENGTH ) {
                continue;
            }

            if ( !empty( $output ) ) {
                $output .= $separator;
            }

            $output .= $attribute->getLabel() . ': ' . $answerField->getAnswer();
        }

        return $output;
    }
}