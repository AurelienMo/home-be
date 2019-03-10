#!/usr/bin/env bash

echo "--------------------"
echo "Check Behat Tests"
echo "--------------------"
vendor/bin/behat --stop-on-failure

echo "--------------------"
echo "Check Coding Style"
echo "--------------------"
vendor/bin/phpcs

echo "--------------------"
echo "Check PHPStan"
echo "--------------------"
vendor/bin/phpstan analyze src


