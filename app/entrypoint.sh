#!/bin/bash
set -e

if [ "$1" = 'sub' ]; then
    php sub.php
fi

php pub.php
