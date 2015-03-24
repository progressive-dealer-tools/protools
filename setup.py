from setuptools import setup, find_packages
import os
import shutil
import stat

if not os.path.exists('scripts'):
    os.makedirs('scripts')
shutil.copyfile('Protools.py', 'scripts/protools')


print "Setting Up ProDB"
if not os.path.exists('/usr/share/php/ProDB'):
    print "Creating directory /usr/share/php/ProDB"
    os.makedirs('/usr/share/php/ProDB')


shutil.copyfile('db/Migration.php', '/usr/share/php/ProDB/Migration.php')
print "Copying Migration.php' to /usr/share/php/ProDB/"
shutil.copyfile('db/MigrationTable.php', '/usr/share/php/ProDB/MigrationTable.php')
print "Copying MigrationTable.php' to /usr/share/php/ProDB/"
shutil.copyfile('db/prodbload.php', '/usr/share/php/prodbload.php')
print "Copying prodbload.php' to /usr/share/php/"
shutil.copyfile('db/ProDB.php', '/usr/local/bin/prodb')
print "Copying ProDB.php' to /usr/local/bin/prodb"

os.chmod('/usr/local/bin/prodb', 0755);
print "Done Setting Up ProDB"

setup(
    name = "Pro Dealer Tools",
    version = "0.1",
    py_modules = ['EnvironmentSetter',],
   scripts = ['scripts/protools'],
)