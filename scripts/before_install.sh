#!/bin/bash
set -euo pipefail

tempdir=/tmp/translate/codedeployupload

[[ -d $tempdir ]] && rm -r $tempdir
mkdir -p $tempdir

mkdir /tmp/$DEPLOYMENT_ID

echo "export tempdir=\"$tempdir\"" > "/tmp/$DEPLOYMENT_ID/.cdvariables";

if [ "$APPLICATION_NAME" == "translate.stage.concretecms.org" ]
then
  echo "export projectdir=\"/home/forge/translate.stage.concretecms.org\"" >> "/tmp/$DEPLOYMENT_ID/.cdvariables";
  echo "export deploydir=\"/home/forge/translate.stage.concretecms.org/releases/$DEPLOYMENT_ID\"" >> "/tmp/$DEPLOYMENT_ID/.cdvariables";
elif [ "$APPLICATION_NAME" == "translate.concretecms.org" ]
then
  echo "export projectdir=\"/home/forge/translate.concretecms.org\"" >> "/tmp/$DEPLOYMENT_ID/.cdvariables";
  echo "export deploydir=\"/home/forge/translate.concretecms.org/releases/$DEPLOYMENT_ID\"" >> "/tmp/$DEPLOYMENT_ID/.cdvariables";
fi
