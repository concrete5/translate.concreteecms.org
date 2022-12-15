#!/bin/bash
set -euo pipefail

source "/tmp/$DEPLOYMENT_ID/.cdvariables"

ln -sfn $deploydir $projectdir/current
cd $projectdir/current && php8.2 ./vendor/bin/concrete5 c5:clear-cache
