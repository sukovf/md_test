#!/bin/bash

set -e

mkdir -p ./output
chown www-data:www-data ./output

# install PHP dependencies
composer i -n -q

"$@"