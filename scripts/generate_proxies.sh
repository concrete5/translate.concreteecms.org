#!/bin/bash
set -euo pipefail

source "/tmp/$DEPLOYMENT_ID/.cdvariables"

cd $deploydir && php7.4 ./vendor/bin/concrete5 orm:generate:proxies
