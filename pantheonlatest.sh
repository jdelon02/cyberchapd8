#!/bin/bash

# exit on any errors:
set -e

# Path to drush goes here:
if [ -d "/var/www/wwwadmin/data/vendor" ]; then
    DRUSH=$(which drush)
else
    DRUSH='/Volumes/NewHome/jdelon02/vendor/bin/drush'
fi

THETIMESTAMP=$(date +%s)  # The "+%s" option to 'date' is GNU-specific.

# Set backupdir so I can use in a couple places.
STR=$(pwd)
BASE=$(basename $PWD)
# SUB='staging'
# CIVIDB="ceacisp_civicrm"
# if grep -q "$SUB" <<< "$STR"; then
#   CIVIDB="staging_civicrm"
# fi

BUPDIR="/var/www/wwwadmin/data/deploybups/$BASE"

if [ -d "$BUPDIR/" ]; then
    echo "Delete files in bup dir older than 10 days."
    $(find $BUPDIR/ -mtime +10 -type f -delete)
else
    echo "$BUPDIR directory does not exist, creating now..."
    $(mkdir -p "$BUPDIR")
fi

echo "gzip current directory to backup directory"
$(tar --exclude='web/sites/default/files' --exclude='*.sql' -cv ./ | gzip > $BUPDIR/$THETIMESTAMP.tar.gz)

# Dump Drupal DB
DRUPALLOG="$BUPDIR/DrupalDB-$THETIMESTAMP.sql"
echo "Dumping Drupal DB to $DRUPALLOG"
$(drush sql-dump --gzip --result-file=$DRUPALLOG)

# Get new tags from remote
echo "fetching all git tags"
git fetch --tags

# Get latest tag name
echo "getting latest tag by name"
LATESTTAG=$(git describe --tags `git rev-list --tags --max-count=1`)

# Checkout latest tag
echo "Checking out latest tag"
git checkout $LATESTTAG

echo "Brent, would you like to play a nice game of chess?..."