#! /bin/bash
#for clearing out and deleting all unwanted recover directories

clear

echo "HELLO WORLD!"

for i in $( ls ); do
	if cd $i; then
		sudo rm *
		cd ..
	else
		echo "failed"
	fi
done

sudo rmdir recup_dir.*
