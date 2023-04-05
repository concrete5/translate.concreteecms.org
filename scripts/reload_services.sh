#!/bin/bash
set -euo pipefail

source "/tmp/$DEPLOYMENT_ID/.cdvariables"

sudo /usr/sbin/service $phpversion-fpm reload
