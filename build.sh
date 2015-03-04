#!/bin/bash
#
# Site build script
#
# This file should contain all necessary steps to build the website. Include here 
# all necessary build steps (e.g. scripts minification, styles compilation etc).
#

CWD=$(pwd)
PROJECT_NAME="NEOS-TYPOSTRAP-DISTRIBUTION"
TYPO3_NEOS_PACKAGE_DIR="${CWD}/Packages/Application/TYPO3.Neos"
M12_FOUNDATION_SITE_PACKAGE_DIR="${CWD}/Packages/Sites/M12.FoundationSite"

function installNeosDevTools() {
  cd "${TYPO3_NEOS_PACKAGE_DIR}/Scripts"
  npm install
  ./install-dev-tools.sh
  cd $CWD
}

function buildM12FoundationSite() {
  cd $M12_FOUNDATION_SITE_PACKAGE_DIR
  bower install --allow-root # this script is called as root in '--preinstall' phase
  npm install
  gulp build --env=Production # build for production by default
  cd $CWD
}


case $@ in
  #
  # This is called when container is being build (and this script is called with --preinstall param)
  #
  *--preinstall*)
    echo "$PROJECT_NAME BUILD SCRIPT: PREINSTALL"
    
    set -e # exit with error if any of the following fail
    
    # Install Beard
    curl -s http://beard.famelo.com/ > "$CWD/bin/beard" && chmod +x "$CWD/bin/beard"
    
    installNeosDevTools
    
    buildM12FoundationSite
    ;;
 
  #
  # This is called when container launches (and script is called without param)
  #
  *)
    echo "$PROJECT_NAME BUILD SCRIPT"
    buildM12FoundationSite
    ;;
esac

echo "$PROJECT_NAME BUILD SCRIPT completed."
