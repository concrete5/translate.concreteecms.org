#!/bin/bash
set -euo pipefail

source "/tmp/$DEPLOYMENT_ID/.cdvariables"

ln -sfn $deploydir $projectdir/current
cd $projectdir/current && $phpversion ./vendor/bin/concrete5 c5:clear-cache
