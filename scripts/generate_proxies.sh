#!/bin/bash
set -euo pipefail

source "/tmp/$DEPLOYMENT_ID/.cdvariables"

cd $deploydir && php8.2 ./vendor/bin/concrete5 orm:generate:proxies
