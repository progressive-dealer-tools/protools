from setuptools import setup, find_packages
import os
import shutil
import stat
from subprocess import check_output

if not os.path.exists('scripts'):
    os.makedirs('scripts')
shutil.copyfile('Protools.py', 'scripts/protools')


print "Setting Up ProDB"

install_to = check_output(["php", "-r", "echo get_include_path();"])
install_to = install_to.split(":")
install_to = filter(lambda path: os.path.isabs(path), install_to)
install_to = install_to[0]

install_to_prodb = os.path.join(install_to, "ProDB")

if not os.path.exists(install_to_prodb):
    print "Creating directory ", install_to_prodb
    os.makedirs(install_to_prodb)


shutil.copyfile('db/Migration.php', os.path.join(install_to_prodb, 'Migration.php'))
print "Copying Migration.php' to ", install_to_prodb


shutil.copyfile('db/MigrationTable.php', os.path.join(install_to_prodb, 'MigrationTable.php'))
print "Copying MigrationTable.php' to ", install_to_prodb


shutil.copyfile('db/prodbload.php', os.path.join(install_to, 'prodbload.php'))
print "Copying prodbload.php' to ", install_to


shutil.copyfile('db/ProDB.php', '/usr/local/bin/prodb')
print "Copying ProDB.php' to /usr/local/bin/prodb"

os.chmod('/usr/local/bin/prodb', 0755);

shutil.copyfile('db/RA_Unit_Database_TestCase.php', 
  os.path.join(install_to_prodb, 'RA_Unit_Database_TestCase.php'))
print "Copying RA_Unit_Database_TestCase.php to", install_to_prodb


print "Done Setting Up ProDB"

setup(
    name = "Pro Dealer Tools",
    version = "0.1",
    py_modules = ['EnvironmentSetter',],
   scripts = ['scripts/protools'],
)