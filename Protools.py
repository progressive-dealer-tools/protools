#!/usr/bin/python
import argparse
import os
from EnvironmentSetter import EnvironmentSetter
from subprocess import call

class Protools:

  def __init__(self):
    self.action_table = {
      'env' : self.run_env,
      'unit-tests' : self.run_unit_tests
    }
    if __name__ == "__main__":
      self.cli_behavior()
 
  def run_unit_tests(self):
    #should probably find bootsrap.php and tests/phpunit, even if called from sub dir
    os.environ['RA_ENV'] = 'test'
    os.environ['RA_MIGRATIONS_FOLDER'] = 'migrations'
    call(["phpunit", "--bootstrap", "bootstrap.php", "tests/phpunit/"])

  def run_env(self):
    envSetter = EnvironmentSetter()
    envSetter.cli_behavior()

  def cli_behavior(self):
      parser = argparse.ArgumentParser(description='Use this script to set env variables')
      parser.add_argument('tool', choices=list(self.action_table.keys()) , action="store",  help='Choose one of the available tools')
      parser.add_argument('variable', action="store",  help='var=val var2=val2 ...', nargs='*')

      args = vars(parser.parse_args())
      self.action_table[args['tool']]()


pt = Protools()