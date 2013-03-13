#!/bin/bash
# Script generates the sql/install.sql file, which contains database structure with empty tables (also without autoincrement values).
# 4th parameter is not required, but if exist, script with output there the mysqldump.
# $> ./sql_schema.sh dbuser dbpass dbname [/path/to/output/file.sql]

REQUIRED_PARAMS=3
SQL_FILE="../../sql/install.sql"

if [ $# -lt $REQUIRED_PARAMS ]; then
    echo "Incorrect number of parameters, $REQUIRED_PARAMS expected ($> ./sql_schema.sh dbuser dbpass dbname)"
    exit 0
fi

# in case when file path is given as a parameter
if [ $# -gt $REQUIRED_PARAMS ]; then
    SQL_FILE="$4"
fi

# dumping all tables structure
mysqldump -d -u$1 -p$2 $3 form_validators form_definitions form_attributes form_attributes_options form_attr_valid form_types | sed 's/ AUTO_INCREMENT=[0-9]*\b//' > $SQL_FILE

# dumping the data from form_validators and form_types tables
mysqldump --skip-triggers --compact --add-locks --no-create-info -u$1 -p$2 $3 form_validators form_types >> $SQL_FILE