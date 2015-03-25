from setuptools import setup, find_packages
import os
import shutil

if not os.path.exists('scripts'):
    os.makedirs('scripts')
shutil.copyfile('Protools.py', 'scripts/protools')

setup(
    name = "Pro Dealer Tools",
    version = "0.1",
    py_modules = ['EnvironmentSetter'],
    scripts = ['scripts/protools'],
)