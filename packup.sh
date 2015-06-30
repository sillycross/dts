#!/bin/bash
echo "packup.sh: 正在删除原dts.tar.gz。";
rm -f dts.tar.gz
#echo "packup.sh: 正在导出sql。";
#mysqldump -h localhost -uDTS -pDTS -d dts > ./gamedata/sql/all.sql
echo "packup.sh: 正在打包..";
tar -czf dts.tar.gz --exclude=dts.tar.gz --exclude=./.* --exclude=gamedata/bak/* --exclude=gamedata/modinit/* --exclude=gamedata/run/* --exclude=gamedata/templates/* --exclude=gamedata/tmp/server/* --exclude=gamedata/tmp/response/* --exclude=img/other/*.ogg --exclude=img/other/*.mp3 .
echo "packup.sh: 完成。";
