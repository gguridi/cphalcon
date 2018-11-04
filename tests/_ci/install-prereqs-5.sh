#!/usr/bin/env bash
#
# This file is part of the Phalcon.
#
# (c) Phalcon Team <team@phalconphp.com>
#
# For the full copyright and license information, please view the LICENSE.txt
# file that was distributed with this source code.

printf "\n" | pecl install --force apcu-4.0.11 &> /dev/null
printf "\n" | pecl install --force igbinary &> /dev/null
printf "\n" | pecl install --force imagick &> /dev/null
printf "\n" | pecl install --force yaml-1.3.1 &> /dev/null
printf "\n" | pecl install --force memcache-2.2.7 &> /dev/null

echo 'extension="mongo.so"' >> "$(phpenv root)/versions/$(phpenv version-name)/etc/php.ini"
echo 'extension="memcached.so"' >> "$(phpenv root)/versions/$(phpenv version-name)/etc/php.ini"
