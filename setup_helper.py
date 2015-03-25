from setuptools import setup, find_packages
import os
import shutil
import stat
from subprocess import check_output
import sys

file_loc = os.path.dirname(os.path.realpath(__file__))

 

print "Setting Up ProDB"

install_to = check_output(["php", "-r", "echo get_include_path();"])
print "PHP include path is " , install_to
install_to = install_to.split(":")
install_to = filter(lambda path: os.path.isabs(path), install_to)
if len(install_to) >0:
  install_to = install_to[0]
else:
  install_to = "."

install_to_prodb = os.path.join(install_to, "ProDB")

if not os.path.exists(install_to_prodb):
    print "Creating directory ", install_to_prodb
    os.makedirs(install_to_prodb)


shutil.copyfile(os.path.join(file_loc , 'db/Migration.php'), os.path.join(install_to_prodb, 'Migration.php'))
print "Copying Migration.php' to ", install_to_prodb


shutil.copyfile(os.path.join(file_loc , 'db/MigrationTable.php'), os.path.join(install_to_prodb, 'MigrationTable.php'))
print "Copying MigrationTable.php' to ", install_to_prodb


shutil.copyfile(os.path.join(file_loc , 'db/prodbload.php'), os.path.join(install_to, 'prodbload.php'))
print "Copying prodbload.php' to ", install_to


shutil.copyfile(os.path.join(file_loc , 'db/ProDB.php'), '/usr/local/bin/prodb')
print "Copying ProDB.php' to /usr/local/bin/prodb"

os.chmod('/usr/local/bin/prodb', 0755);

shutil.copyfile(os.path.join(file_loc , 'db/RA_Unit_Database_TestCase.php'), 
  os.path.join(install_to_prodb, 'RA_Unit_Database_TestCase.php'))
print "Copying RA_Unit_Database_TestCase.php to", install_to_prodb


print "Done Setting Up ProDB"