<?php
$dsn = "pgsql:"
. "host=ec2-3-221-100-217.compute-1.amazonaws.com;"
. "dbname=dasaur93oo75cr;"
. "user=swbcsfjlkdpmxy;"
. "port=5432;"
. "sslmode=require;"
. "password=230f3b3e18c1b36230767101fb25aea119911c36ba6cc2af15b15822225b1e9a";

$db = new PDO($dsn);
echo "DB Status: ";
if ($db) {
echo "Connected!"."<br>";
} else {
echo "Faild..."."<br>";
}