from setuptools import setup, find_packages
import os
import shutil

if not os.path.exists('scripts'):
    os.makedirs('scripts')
shutil.copyfile('EnvironmentSetter.py', 'scripts/env_manager')

setup(
    name = "RA Environment Setter",
    version = "0.1",
   scripts = ['scripts/env_manager'],
)