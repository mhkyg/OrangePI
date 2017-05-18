#!/bin/sh
\cp -f ~/OrangePIStuff/motion/motion_daemon /etc/init.d/motion_daemon
chmod +x /etc/init.d/motion_daemon
echo "Update.rc"
update-rc.d motion_daemon defaults
echo "Starting"
service motion_daemon restart
echo "status"
systemctl status motion_daemon.service