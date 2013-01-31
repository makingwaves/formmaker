/*
    Script updates database from version 1.4.x to 1.5.x
*/


INSERT INTO 'form_validators' (
'id' ,
'type' ,
'description' ,
'regex'
)
VALUES (
NULL , 'Regex', 'Date (year only)', '/^([1-2][0-9]{3})$/'