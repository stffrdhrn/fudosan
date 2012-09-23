#!/bin/bash

DIR=$(dirname $0)
DB_HOST='localhost'
DB_PORT=5984
DB_NAME='fudosan'

auth=""
user=$1
if [ ! -z $user ] ; then
  read -s -p 'password: ' password
  auth="--user $user:$password"
fi

db_url="http://$DB_HOST:$DB_PORT/$DB_NAME/"

# Create Database
curl $auth -X PUT $db_url
curl $auth -d "@$DIR/login-design.json" -X PUT "${db_url}_design/login"
curl $auth -d "@$DIR/login-design.json" -X PUT "${db_url}_design/property"

curl $db_url
