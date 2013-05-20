<?php

/**
 * Class includes a fetch functions used in Answers context
 */
class AnswersFunctionCollection
{
    /**
     * Method fetches the answers basing. All parameters are optional
     * @param int|null $form_id
     * @param int|null $offset
     * @param int|null $limit
     * @return array
     */
    public function getAnswers( $form_id, $offset, $limit )
    {
        return array( 'result' => formAnswers::getAnswers( $form_id, $offset, $limit ) );
    }

    /**
     * This fetch returns the total count of answers. The only limit can be a $form_id, but it's not required.
     * @param int $form_id
     * @return array
     */
    public function getAnswersCount( $form_id )
    {
        return array( 'result' => formAnswers::getAnswersCount( $form_id ) );
    }
}