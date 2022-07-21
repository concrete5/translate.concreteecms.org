#!/bin/bash
set -euo pipefail

source "/tmp/.cdvariables"

cd $deploydir && php7.4 ./vendor/bin/concrete5 orm:generate:proxies
