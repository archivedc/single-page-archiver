#!/bin/bash
set -e

cd /data

while read -d $'\0' file; do
    hash=`sha512sum $file | awk '{ print $1 }'`
    echo "$hash" "$file" >> hashes.sha512
done < <(find $1 -type f -print0)