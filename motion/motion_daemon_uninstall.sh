#!/bin/sh
service motion_daemon stop
echo "update.rc"
update-rc.d -f motion_daemon remove
echo "file remove"
rm /etc/init.d/motion_daemon

