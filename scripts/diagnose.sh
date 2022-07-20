#!/bin/bash

# Gather and output lifecycle event information
deploy=$1

result=0
failStyle="\\\e[1;37m\\\u001b[31m"
successStyle="\\\u001b[32m"
skippedStyle="\\\u001b[37m"

instances=`aws deploy list-deployment-instances --deployment-id="$deploy" --output json | jq -r '.instancesList[] as $i | $i'`
for instance in "${instances[@]}"; do
    echo "Instance $instance:"
    aws deploy get-deployment-instance --deployment-id="$deploy" --instance-id="$instance" > $instance.txt

    # Output general status
    echo $'\nEvents:'
    events=`cat $instance.txt | \
        jq -r '.instanceSummary.lifecycleEvents[] as $e | "  " + $e.lifecycleEventName + ": " + $e.status' | \
        sed -e 's/Failed/'$failStyle'Failed\\\e[0m/g' | \
        sed -e 's/Succeeded/'$successStyle'Succeeded\\\e[0m/g' | \
        sed -e 's/Skipped/'$skippedStyle'Skipped\\\e[0m/g'`
    printf "$events"
    echo ""

    # Output diagnostics
    diagnostics=`cat $instance.txt | \
        jq -r '.instanceSummary.lifecycleEvents[] | select(.diagnostics and .status != "Succeeded") | .diagnostics | .errorCode + ": " + .scriptName + "\n\n" + .logTail'`

    if [ ! -z "$diagnostics" ]; then
        echo $'\nDiagnostics:'
        echo "$diagnostics" | sed 's/^/  /'
        result=1
    fi
done

exit "$result"
