#!/bin/bash

cd ../
docker run -it --rm -v `pwd`:/opt/project php_8.0-rc-xdebug-image /opt/project/vendor/phpunit/phpunit/phpunit -dxdebug.coverage_enable=1 --coverage-clover /opt/phpstorm-coverage/go_lib@test.xml --whitelist /opt/project/src --no-configuration /opt/project/test --teamcity --cache-result-file=/opt/project/.phpunit.result.cache