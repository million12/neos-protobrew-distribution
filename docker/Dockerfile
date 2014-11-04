FROM million12/typo3-neos-abstract:latest
MAINTAINER Marcin Ryzycki marcin@m12.io

# ENV: Repository for installed TYPO3 Neos distribution 
ENV TYPO3_NEOS_REPO_URL https://github.com/million12/neos-typostrap-distribution.git

ENV NEOS_APP_SITE_PACKAGE M12.FoundationSite
ENV NEOS_APP_VHOST_NAMES neos-typostrap dev.neos-typostrap behat.dev.neos-typostrap

# Pre-install TYPO3 Neos
RUN . /build-typo3-neos/pre-install-typo3-neos.sh
