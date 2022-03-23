#!/bin/bash
set -e

cd /data

touch hashes.sha512

while read h; do
    hash=`echo -n $h | awk '{ print $1 }'`
    ofname=`echo -n $h | awk '{ print $2 }'`

    while read -d $'\0' file; do
        nhash=`sha512sum $file | awk '{ print $1 }'`
        if [[ "$hash" == "$nhash" ]]; then
            if cmp -s "$file" "$ofname"; then
                ln -s -L -r -f "$ofname" "$file"
            fi
        fi
    done < <(find $1 -type f -print0)
done <hashes.sha512