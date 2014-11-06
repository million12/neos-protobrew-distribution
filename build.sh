#!/bin/sh
#
# Site build script
#
# This file should contain all necessary steps to build the website. Include here 
# all necessary build steps (e.g. scripts minification, styles compilation etc).
#

BUILD_DIR="Packages/Sites/M12.FoundationSite/"

case $@ in
  #
  # This is called when container is being build (and this script is called with --preinstall param)
  #
  *--preinstall*)
    echo "SITE BUILD SCRIPT: PREINSTALL"
    
    # Install required tools globally
    npm install -g gulp bower
    
    # Install site packages
    set -e # exit with error if any of the following fail
    cd $BUILD_DIR
    bower install --allow-root
    npm install
    gulp build --env=Production
    ;;
 
  #
  # This is called when container launches (and script is called without param)
  #
  *)
    echo "SITE BUILD SCRIPT"
    cd $BUILD_DIR
    bower install
    npm install
    gulp build --env=Production # build for production by default
    ;;
esac

echo "SITE BUILD SCRIPT completed."
