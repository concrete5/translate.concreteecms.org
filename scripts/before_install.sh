#!/bin/bash
set -euo pipefail

cd "$(dirname "${BASH_SOURCE[0]}")"

source ../.codedeployvars
tempdir=/tmp/codedeploy/codedeployupload/$jobid

[[ -d $tempdir ]] && rm -r $tempdir
mkdir -p $tempdir

mkdir /tmp/$DEPLOYMENT_ID

echo "export tempdir=\"$tempdir\"" > "/tmp/$DEPLOYMENT_ID/.cdvariables";

