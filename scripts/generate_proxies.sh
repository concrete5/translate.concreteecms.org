#!/bin/bash
set -euo pipefail

source "/tmp/$DEPLOYMENT_ID/.cdvariables"

cd $deploydir && $phpversion ./vendor/bin/concrete5 orm:generate:proxies
