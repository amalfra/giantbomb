#! /bin/sh

find . -iname '*.php' -path "./src/*" -or -path "./tests/*" | while read line; do
  echo "Linting file - $line"
  lint_cmd="$(php -l $line)"
  rs=$?
  if [ $rs != 0 ]; then
    exit $rs
  fi
done
