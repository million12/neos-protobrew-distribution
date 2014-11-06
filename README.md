# Typostrap.io distribution of TYPO3 Neos
[![Circle CI](https://circleci.com/gh/million12/neos-typostrap-distribution.png?style=badge)](https://circleci.com/gh/million12/neos-typostrap-distribution)

Open-source version of [Typostrap.io](http://typostrap.io/) project, the prototyping tool built on top of [TYPO3 Neos](http://neos.typo3.org/) CMS and [Zurb Foundation](http://foundation.zurb.com/) framework.


## Background

This is a **Typostrap distribution** of TYPO3 Neos CMS. It is based on TYPO3 Neos **Base distribution**, but with [M12.Foundation](https://github.com/million12/M12.Foundation) plugin installed (and its dependencies). M12.Foundation aims to implement all Foundation components, in the best possible way, inside TYPO3 Neos CMS.

This work uses [Docker](https://docker.com/) containers. It will launch one container with MariaDB database and another container with Nginx/PHP to serve TYPO3 Neos. Therefore some familiarity with Docker is desired.

## Usage

+ You will need a machine with Docker daemon to run Docker containers. The easiest way to start with Docker is using [Boot2Docker](http://boot2docker.io/). Follow the documentation from there. 
+ Run `boot2docker ip` to get Docker host IP (we will refer to it as a DOCKER_IP).
+ Add line with `DOCKER_IP neos-typostrap dev.neos-typostrap` to `/etc/hosts` file on your machine. 
+ You will need [fig](http://www.fig.sh/) installed on your machine, arguably the simplest tool to orchestrate running multiple Docker containers. Follow the documentation there.
+ Clone this repository (optionally: just grab `fig.yml` file from it).
+ Run `fig pull` to pull the necessary containers from [hub.docker.com](https://hub.docker.com/). Note: this might take a little while (~1.4GB).
+ Run `fig up`.  
  Wait till all containers are running. You will see messages about setting up TYPO3 Neos and at the end you will see something like `success: nginx entered RUNNING state` and `success: php-fpm entered RUNNING state`.
+ Go to [http://neos-typostrap:8899/neos](http://neos-typostrap:8899/neos) to login to the back-end. 
  Use username: `admin`, password: `password` to sign in.
+ The front-end page is available under [http://neos-typostrap:8899/](http://neos-typostrap:8899). Caveat: **It is completely blank** until you add and publish some content there, so don't be surprised.

At any time you can stop containers by pressing CTRL+C. Later on simply run `fig up` again to continue from where you left it.

### Development

Fig starts SSH container on port :5555. Run `ssh -p 5555 www@DOCKER_IP` and you are inside. The code resides inside `~/neos-typostrap` directory. You can use this access to run `./flow` tool and edit/change/upload files via SFTP. 

Authorisation to this SSH is possible via your public key. Set `IMPORT_GITHUB_PUB_KEYS` in `fig.yml` to your GitHub username and your key will be imported from there automatically (using public GitHub API).


## Author(s)

* Marcin Ryzycki marcin@m12.io  
* Samuel Ryzycki samuel@m12.io
