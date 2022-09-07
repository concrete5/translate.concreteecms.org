#!/bin/bash
set -euo pipefail

source "/tmp/$DEPLOYMENT_ID/.cdvariables"

# count number of directories in /releases
releasedircount=$(find $projectdir/releases/ -mindepth 1 -maxdepth 1 -type d | wc -l)
# subtract most recent 5 releases
((releasedircount-=5))
# delete old release directories
if (( releasedircount > 0 )); then
    find $projectdir/releases/ -maxdepth 1 -type d -printf '%T@\t%p\n' | sort -r | tail -n $releasedircount | sed 's/[0-9]*\.[0-9]*\t//' | xargs -n1 rm -rf
fi;
