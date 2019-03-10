#!/bin/bash
# PHPCode sniffer - PHP Unit pre-commit hook for git - Behat test
#
# @author Aurélien Morvan <contact@aurelien-morvan.fr>

PHPCS_BIN=vendor/bin/phpcs # example: /usr/local/phpcs
BEHAT=vendor/bin/behat
PHPSTAN=vendor/bin/phpstan

TMP_STAGING=".tmp_staging"

# simple check if code sniffer is set up correctly
if [ ! -x $PHPCS_BIN ]; then
    echo "PHP CodeSniffer bin not found or executable -> $PHPCS_BIN"
    exit 1
fi

# simple check if behat is set up correctly
if [ ! -x $BEHAT ]; then
    echo "Behat bin not found or executable -> $BEHAT"
    exit 1
fi

# simple check if phpstan is set up correctly
if [ ! -x $PHPSTAN ]; then
    echo "Phpstan bin not found or executable -> $PHPSTAN"
    exit 1
fi

# stolen from template file
if git rev-parse --verify HEAD
then
    against=HEAD
else
    # Initial commit: diff against an empty tree object
    against=4b825dc642cb6eb9a060e54bf8d69288fbee4904
fi

# this is the magic:
# retrieve all files in staging area that are added, modified or renamed
# but no deletions etc
FILES=$(git diff-index --name-only --cached --diff-filter=ACMR $against -- )

if [ "$FILES" == "" ]; then
    exit 0
fi

# create temporary copy of staging area
if [ -e $TMP_STAGING ]; then
    rm -rf $TMP_STAGING
fi
mkdir $TMP_STAGING

# match files against whitelist
FILES_TO_CHECK=""
for FILE in $FILES
do
    echo "$FILE" | egrep -q "$PHPCS_FILE_PATTERN"
    RETVAL=$?
    if [ "$RETVAL" -eq "0" ]
    then
        FILES_TO_CHECK="$FILES_TO_CHECK $FILE"
    fi
done

if [ "$FILES_TO_CHECK" == "" ]; then
    exit 0
fi

# execute the code sniffer
if [ "$PHPCS_IGNORE" != "" ]; then
    IGNORE="--ignore=$PHPCS_IGNORE"
else
    IGNORE=""
fi

if [ "$PHPCS_SNIFFS" != "" ]; then
    SNIFFS="--sniffs=$PHPCS_SNIFFS"
else
    SNIFFS=""
fi

if [ "$PHPCS_ENCODING" != "" ]; then
    ENCODING="--encoding=$PHPCS_ENCODING"
else
    ENCODING=""
fi

if [ "$PHPCS_IGNORE_WARNINGS" == "1" ]; then
    IGNORE_WARNINGS="-n"
else
    IGNORE_WARNINGS=""
fi

# Copy contents of staged version of files to temporary staging area
# because we only want the staged version that will be commited and not
# the version in the working directory
STAGED_FILES=""
for FILE in $FILES_TO_CHECK
do
  ID=$(git diff-index --cached $against $FILE | cut -d " " -f4)

  # create staged version of file in temporary staging area with the same
  # path as the original file so that the phpcs ignore filters can be applied
  mkdir -p "$TMP_STAGING/$(dirname $FILE)"
  git cat-file blob $ID > "$TMP_STAGING/$FILE"
  STAGED_FILES="$STAGED_FILES $TMP_STAGING/$FILE"
done


if [ $RETVAL2 -ne 0 ]; then
    echo "$OUTPUT2" | less
fi

if [ $RETVAL2 == "2" ]; then
	exit 1
fi

echo "$PHPCS_BIN $STAGED_FILES"
OUTPUT=$($PHPCS_BIN $STAGED_FILES)
RETVAL=$?

if [ $RETVAL -ne 0 ]; then
    echo "$OUTPUT" | less
fi

echo "$BEHAT"
OUTPUT3=$($BEHAT)
RETVAL3=$?

echo $RETVAL3

echo "$PHPSTAN"
OUTPUT4=$($PHPSTAN) analyze src/ --level=7
RETVAL4=$?

echo $RETVAL4

if [ $RETVAL3 -ne 0 ]; then
    echo "$OUTPUT3" | less
fi

if [ $RETVAL3 == "2" ]; then
	exit 1
fi

if [ $RETVAL4 -ne 0 ]; then
    echo "$OUTPUT4" | less
fi

if [ $RETVAL4 == "2" ]; then
	exit 1
fi

# delete temporary copy of staging area
rm -rf $TMP_STAGING

exit $RETVAL
