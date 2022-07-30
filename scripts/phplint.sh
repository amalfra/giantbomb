#! /bin/sh

find . -iname '*.php' -path "./src/*" -or -path "./tests/*" | while read file; do
  echo "Linting file - $file"
  lint_cmd="$(php -l $file)"
  rs=$?
  if [ $rs != 0 ]; then
    exit $rs
  fi

  echo "PHP_CodeSniffer file - $file"
  lint_cmd="$(./vendor/bin/phpcs -h $file)"
  rs=$?
  if [ $rs != 0 ]; then
    exit $rs
  fi
  lint_cmd="$(./vendor/bin/phpcbf -h $file)"
  rs=$?
  if [ $rs != 0 ]; then
    exit $rs
  fi
done
