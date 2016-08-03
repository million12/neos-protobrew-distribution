#!/bin/bash
#
# Site build script
#
# This file should contain all necessary steps to build the website. Include here 
# all necessary build steps (e.g. scripts minification, styles compilation etc).
#

CWD=$(pwd)
PROJECT_NAME="NEOS-PROTOBREW-DISTRIBUTION"
SITE_PACKAGE_DIR="${CWD}/Packages/Sites/Pb.Site"


function buildSitePackage() {
  echo "Building Pb.Site..."
  cd $SITE_PACKAGE_DIR

  # Initialise NVM
  set +u && source $NVM_DIR/nvm.sh && nvm install && nvm use

  npm install
  npm run build:prod
  cd $CWD
}


case $@ in
  #
  # This is called when container is being build (and this script is called with --post-build param)
  #
  *--post-build*)
    echo && echo "$PROJECT_NAME BUILD SCRIPT: POST-BUILD"

    set -e # exit with error if any of the following fail
    buildSitePackage
    ;;
 
  #
  # This is called when container launches (and script is called without param)
  #
  *)
    echo && echo "$PROJECT_NAME BUILD SCRIPT"
    git config --global user.email "www@build.user" &&  git config --global user.name "WWW User"

    # Build site package, if needed.
    [ "${T3APP_ALWAYS_DO_PULL^^}" = TRUE ] && buildSitePackage

    echo "Done."
esac

echo "$PROJECT_NAME BUILD SCRIPT completed."
