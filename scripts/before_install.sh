#!/bin/bash
set -euo pipefail

tempdir=/tmp/codedeployupload

[[ -d $tempdir ]] && rm -r $tempdir
mkdir -p $tempdir

if [ "$APPLICATION_NAME" == "translate.stage.concretecms.org" ]
then
  echo "export projectdir=\"/home/forge/translate.stage.concretecms.org\"" > "/tmp/.cdvariables";
  echo "export deploydir=\"/home/forge/translate.stage.concretecms.org/releases/$DEPLOYMENT_ID\"" >> "/tmp/.cdvariables";
elif [ "$APPLICATION_NAME" == "translate.concretecms.org" ]
then
  echo "export projectdir=\"/home/forge/translate.concretecms.org\"" > "/tmp/.cdvariables";
  echo "export deploydir=\"/home/forge/translate.concretecms.org/releases/$DEPLOYMENT_ID\"" >> "/tmp/.cdvariables";
fi
