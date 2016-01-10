FROM million12/typo3-flow-neos-abstract:latest
MAINTAINER Marcin Ryzycki marcin@m12.io

# ENV: Repository for installed Neos CMS distribution
ENV \
  T3APP_BUILD_REPO_URL="https://github.com/million12/neos-protobrew-distribution.git" \
  T3APP_NEOS_SITE_PACKAGE=M12.FoundationSite \
  T3APP_VHOST_NAMES="neos-protobrew.local dev.neos-protobrew.local behat.dev.neos-protobrew.local"

# Pre-install project into /tmp directory
RUN . /build-typo3-app/pre-install-typo3-app.sh
