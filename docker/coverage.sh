#!/bin/bash

cd ../
#docker run -it --rm -v `pwd`:/opt/project php_8.0-rc-xdebug-image /opt/project/vendor/phpunit/phpunit/phpunit -dxdebug.mode=coverage --coverage-clover /opt/phpstorm-coverage/go_lib@test.xml --whitelist /opt/project/src --no-configuration /opt/project/test --teamcity --cache-result-file=/opt/project/coverage/.phpunit.result.cache
docker run -it --rm -v `pwd`:/opt/project php_8.0-rc-xdebug-image /opt/project/vendor/phpunit/phpunit/phpunit --coverage-html /opt/project/coverage/  --whitelist /opt/project/src  /opt/project/test