#!/bin/bash

/usr/bin/php 'rename.php' $1 $2;
/usr/bin/php 'scan.php'
/usr/bin/php 'scan.php'
/usr/bin/php 'today.php'