#!/usr/bin/python
import argparse
import os


class EnvironmentSetter:
  def cli_behavior(self):
      parser = argparse.ArgumentParser(description='Use this script to set env variables')
      parser.add_argument('action', choices=["save", "set"], action="store",  help='Save the values to /etc/environment, or set them temporarily')
      parser.add_argument('variable', action="store",  help='var=val var2=val2 ...', nargs='*')

      args = vars(parser.parse_args())
      self.action = args['action'].lower()

      self.parse_cli_variables(args['variable'])

      if (self.action == "set"):
         self.set()
      elif(self.action == "save"):
        self.save()

  def parse_cli_variables(self, variables):
    cli_variables = map (lambda x: x.split("=")[0], variables)
    cli_values = map (lambda x: x.split("=")[1], variables)
    self.variables_to_rewrite = dict(zip(cli_variables, cli_values))

  def parse_line(self, line):
    variable, value = line.split("=")
    if (variable in self.variables_to_rewrite):
      value = self.variables_to_rewrite[variable]
      del self.variables_to_rewrite[variable]
    self.write_var_expr(variable, value)

  def should_parse(self, line):
    return ("=" in line) and line[0].isalpha()

  def env_file(self):
    f = open(self.environment_filename)
    lines = f.read().split('\n')
    f.close()

    if (lines[-1:][0] == ""): #don't return the empty string, corresponding to the last \n
      return lines[:-1]
    return lines

  def open_env_file(self):
    self.environment_file = open(self.environment_filename, 'w')

  def write_var_expr(self, variable, value):
    self.write_env_file(variable + "=" + value)

  def write_env_file(self, line):
    self.environment_file.write(line +"\n")

  def close_env_file(self):
    self.environment_file.close()

  def set(self):
    print "NOT IMPLEMENTED"

  def save(self):
    self.check_permissions()
    lines = self.env_file()
    self.open_env_file()

    #Write/Rewrite all existing lines
    for line in lines: 
      if self.should_parse(line):
        self.parse_line(line)
      else:
        self.write_env_file(line)

    #Append any new variabls to the end of the file
    for variable in self.variables_to_rewrite:
      self.write_var_expr(variable, self.variables_to_rewrite[variable])
      
  def check_permissions(self):
    if not(os.access(self.environment_filename, os.W_OK | os.R_OK)):
      print "Unable to access", self.environment_filename, "perhaps try rerunning as root"
      exit(1)
  def __init__(self):
    self.environment_filename = "/etc/environment"
    if __name__ == "__main__":
      self.cli_behavior()

envSetter = EnvironmentSetter()

#lines = open('/etc/environment').read().split('\n')