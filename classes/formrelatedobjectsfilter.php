<?php

/**
 * Extended attribute filter for fetching the nodes where given form_id is used
 */
class FormRelatedObjectsFilter 
{
    /**
     * Method which injects the sql part into query
     * @param array $params
     * @return array
     */
    public function getObjectsList( $params )
    {
        $definition = formDefinitions::definition();
        $tables = ", $definition[name], ezcontentobject_attribute ";
        $joins = array(
            "ezcontentobject_attribute.contentobject_id=ezcontentobject.id",
            'ezcontentobject_attribute.data_type_string LIKE "' . formType::DATA_TYPE_STRING . '"',
            'ezcontentobject_attribute.data_text=' . $params['form_id'],
            'ezcontentobject_tree.contentobject_version=ezcontentobject_attribute.version'
        );
        $joins = join(' AND ', $joins) . ' AND ';
        
        return array(
            'tables'    => $tables,
            'joins'     => $joins,
            'columns'   => ''
        );
    }
}