#!/bin/bash
#
# Script generates the sql/dump.sql file, which contains database tables structure with their data.
# It's helpful when working with legacy versions of the Bundle.
# 4th parameter is not required, but if exist, script with output there the mysqldump.
#
# Command that runs the script:
# $ ./sql_schema.sh dbuser dbpass dbname [/path/to/output/file.sql]

REQUIRED_PARAMS=3
SQL_FILE="../../sql/dump.sql"

if [ $# -lt $REQUIRED_PARAMS ]; then
    echo "Incorrect number of parameters, $REQUIRED_PARAMS expected ($> ./sql_schema.sh dbuser dbpass dbname)"
    exit 0
fi

# in case when file path is given as a parameter
if [ $# -gt $REQUIRED_PARAMS ]; then
    SQL_FILE="$4"
fi

# dumping all tables structure
mysqldump -d -u$1 -p$2 $3 form_validators form_definitions form_types form_attributes form_attributes_options form_attr_valid form_answers form_answers_attributes > $SQL_FILE

# dumping the data
mysqldump --skip-triggers --compact --add-locks --no-create-info -u$1 -p$2 $3 form_validators form_definitions form_types form_attributes form_attributes_options form_attr_valid form_answers form_answers_attributes >> $SQL_FILE