# dbwebb-se-mvc-v2

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/vic0rsan/mvc-report/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/vic0rsan/mvc-report/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/vic0rsan/mvc-report/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/vic0rsan/mvc-report/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/vic0rsan/mvc-report/badges/build.png?b=master)](https://scrutinizer-ci.com/g/vic0rsan/mvc-report/build-status/master)

A minimal website created in php-Symfony. The purpose of the website is the document and examine the assignments for the course MVC. Lastly, the website features a simple Five Card Poker game which serving as a final examination project for the course. The purpose of the project is to combine the knowledge that have I gained from performing the previous course moments.

In order to setup the website, you may follow the instructions below:

Step 1: Clone the repo
```
git clone https://github.com/vic0rsan/mvc-report.git
```

Step 2: Install the dependencies (assuming you have PHP, Composer and NPM installed)
```
cd mvc-report
composer install
npm install
```
Step 3: Deploy the web-application
```
php -S localhost:8888 -t public
```