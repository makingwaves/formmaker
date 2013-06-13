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

    /**
     * This fetch returns a list of forms defined in a system.
     * @param boolean $only_collectors - it set as true, method will return only forms which collects the data in database
     * @return array
     */
    public function getFormsList( $only_collectors )
    {
        $forms = formDefinitions::getAllForms();
        if ( $only_collectors )
        {
            foreach ( $forms as $i => $form )
            {
                if ( $form->attribute( 'store_action' ) == 0 )
                {
                    unset( $forms[$i] );
                }
            }
        }

        return array( 'result' => $forms );
    }
}