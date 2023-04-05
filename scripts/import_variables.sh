#!/bin/bash
set -euo pipefail

source "/tmp/$DEPLOYMENT_ID/.cdvariables"
source "$tempdir/.codedeployvars"

echo "export phpversion=\"$phpversion\"" >> "/tmp/$DEPLOYMENT_ID/.cdvariables";
echo "export projectdir=\"$projectdir\"" >> "/tmp/$DEPLOYMENT_ID/.cdvariables";
echo "export deploydir=\"$projectdir/releases/$DEPLOYMENT_ID\"" >> "/tmp/$DEPLOYMENT_ID/.cdvariables";

