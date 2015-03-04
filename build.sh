#!/bin/bash
#
# Site build script
#
# This file should contain all necessary steps to build the website. Include here 
# all necessary build steps (e.g. scripts minification, styles compilation etc).
#

CWD=$(pwd)
RUN_BEARD=${RUN_BEARD:=false}

PROJECT_NAME="NEOS-TYPOSTRAP-DISTRIBUTION"
TYPO3_NEOS_PACKAGE_DIR="${CWD}/Packages/Application/TYPO3.Neos"
M12_FOUNDATION_SITE_PACKAGE_DIR="${CWD}/Packages/Sites/M12.FoundationSite"

function installNeosDevTools() {
  echo "Installing Neos dev tools..."
  mkdir -p /data/www && chown www:www /data/www # needed for `npm install for its cache, called from ./install-dev-tools.sh
  cd "${TYPO3_NEOS_PACKAGE_DIR}/Scripts"
  chown -R www:www . # Fix perms for current dir as they are not set to www user yet. Needed for ./install-dev-tools.sh which cannot be run as root (bower error)
  su www -c "./install-dev-tools.sh" # Run as www user: Bower will exit if it's run as root user
  rm -rf /data/www # This function is callled only during docker build. We don't need to embed this dir in the image...
  cd $CWD
}

function buildM12FoundationSite() {
  echo "Building M12.FoundationSite..."
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
    echo && echo "$PROJECT_NAME BUILD SCRIPT: PREINSTALL"
    
    set -e # exit with error if any of the following fail
    
    # Install Beard
    echo "Installing Beard..."
    curl -s http://beard.famelo.com/ > "$CWD/bin/beard" && chmod +x "$CWD/bin/beard"
    
    installNeosDevTools
    
    buildM12FoundationSite
    ;;
 
  #
  # This is called when container launches (and script is called without param)
  #
  *)
    echo && echo "$PROJECT_NAME BUILD SCRIPT"
    git config --global user.email "www@build.user" &&  git config --global user.name "WWW User"
    
    buildM12FoundationSite
    
    if [ "${RUN_BEARD^^}" = TRUE ]; then
      bin/beard patch
      grunt --gruntfile=Packages/Application/TYPO3.Neos/Scripts/Gruntfile.js build
    fi
esac

echo "$PROJECT_NAME BUILD SCRIPT completed."
