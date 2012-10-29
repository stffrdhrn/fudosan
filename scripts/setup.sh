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

for design in login property association ; do
  curl $auth -d "@$DIR/${design}-design.json" -X PUT "${db_url}_design/${design}"
  # to backup database design uncomment this line
  # curl $auth "${db_url}_design/${design}" | sed -e 's/,"_rev":".\{34\}"//' > $DIR/${design}-design.json 
done

curl $db_url


