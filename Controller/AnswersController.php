<?php

namespace MakingWaves\FormMakerBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use MakingWaves\FormMakerBundle\Entity\FormTypes;
/**
 * Class AnswersController
 * @package MakingWaves\FormMakerBundle\Controller
 */
class AnswersController extends Controller
{
    /**
     * Indicate how many answers should be displayed on one page
     * @var int
     */
    CONST ITEMS_PER_PAGE = 20;

    /**
     * Allowed length of summary text
     * @var int
     */
    CONST SUMMARY_STRING_LENGTH = 120;

    /**
     * Set of attribute types which will be used when generating answer summary
     * @var array
     */
    private $summaryAttributes = array( FormTypes::TEXTLINE_ID, FormTypes::SELECT_ID, FormTypes::RADIO_ID );

    /**
     * Action for displaying and paginating forms answers list
     * @param int $offset
     * @param int|null $formId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction( $offset, $formId = null )
    {
        $userNames = array();
        $summaries = array();
        $userService = $this->get( 'formmaker.user' );
        $results = $this->getAnswers( $offset, $formId );
        $allResultsCount = $this->getAnswersCount( $formId );

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
            'FormMakerBundle:Answers:list.html.twig',
            array(
                'results' => $results,
                'userNames' => $userNames,
                'summaries' => $summaries,
                'allResultsCount' => $allResultsCount,
                'pages' => $this->getNumberOfPages( $allResultsCount ),
                'itemsPerPage' => self::ITEMS_PER_PAGE,
                'currentPage' => $this->getCurrentPage( $offset ),
                'offset' => $offset,
                'formDefinitions' => $this->getDoctrine()->getRepository( 'FormMakerBundle:FormDefinitions' )->findAll(),
                'formId' => $formId
            )
        );
    }

    /**
     * Returns the count of all answers
     * @param int|null $formId
     * @return mixed
     */
    private function getAnswersCount( $formId = null )
    {
        $repository = $this->getDoctrine()->getRepository( 'FormMakerBundle:FormAnswers' );
        $query = $repository->createQueryBuilder( 'fa' )
            ->select( 'count(fa.id)' );

        if ( !empty( $formId ) ) {
            $query->where( 'fa.definition=' . $formId );
        }

        $result = $query->getQuery()->getSingleScalarResult();

        return $result;
    }

    /**
     * Returns the array of answers
     * @param int $offset
     * @param int|null $formId
     * @return mixed
     */
    private function getAnswers( $offset, $formId = null )
    {
        $repository = $this->getDoctrine()->getRepository( 'FormMakerBundle:FormAnswers' );
        $query = $repository->createQueryBuilder( 'fa' )
            ->orderBy( 'fa.answerDate', 'DESC' )
            ->setFirstResult( $offset )
            ->setMaxResults( self::ITEMS_PER_PAGE );

        if ( !empty( $formId ) ) {
            $query->where( 'fa.definition=' . $formId );
        }

        $results = $query->getQuery()->getResult();

        return $results;
    }

    /**
     * Returns the number of pages with results
     * @param int $resultsCount
     * @return int
     */
    private function getNumberOfPages( $resultsCount )
    {
        $pages = round( $resultsCount / self::ITEMS_PER_PAGE );
        // there's always at least one page (which is empty in case of no results)
        $pages = $pages == 0 ? 1 : $pages;

        return $pages;
    }

    /**
     * Returns the integer value of current page
     * @param int $offset
     * @return float
     */
    private function getCurrentPage( $offset )
    {
        $currentPage = $offset / self::ITEMS_PER_PAGE + 1;
        return $currentPage;
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
        $answerAttributes = $this->getAnswerAttributes( $answerId );

        foreach( $answerAttributes as $answerField ) {

            $attribute = $answerField->getAttribute();
            if ( !in_array( $attribute->getType()->getStringId(), $this->summaryAttributes ) ||
                 strlen( $output ) >= self::SUMMARY_STRING_LENGTH ) {

                continue;
            }

            if ( !empty( $output ) ) {
                $output .= $separator;
            }

            $output .= $attribute->getLabel() . ': ' . $answerField->getAnswer();
        }

        return $output;
    }

    /**
     * Action displays the details of one particular answer
     * @param int $answerId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detailsAction( $answerId )
    {
        $answer = $this->getDoctrine()->getRepository( 'FormMakerBundle:FormAnswers' )->find( $answerId );
        $answerAttributes = $this->getAnswerAttributes( $answerId );

        return $this->render(
            'FormMakerBundle:Answers:details.html.twig',
            array(
                'answer' => $answer,
                'answerAttributes' => $answerAttributes
            )
        );
    }

    /**
     * Returns the set of answer attributes
     * @param int $answerId
     * @return array
     */
    private function getAnswerAttributes( $answerId )
    {
        $answerAttributes = $this->getDoctrine()->getRepository( 'FormMakerBundle:FormAnswersAttributes' )->findBy( array(
            'answerId' => $answerId
        ) );

        return $answerAttributes;
    }
}