#!/bin/bash
set -euo pipefail

source "/tmp/$DEPLOYMENT_ID/.cdvariables"

# count number of directories in /releases
releasedircount=$(find $projectdir/releases/ -mindepth 1 -maxdepth 1 -type d | wc -l)

# delete old release directories
if (( releasedircount > 5 )); then
    find $projectdir/releases/ -maxdepth 1 -type d -printf '%T@\t%p\n' | sort -r | tail -n $(($releasedircount - 5)) | sed 's/[0-9]*\.[0-9]*\t//' | xargs -n1 rm -rf
fi;
