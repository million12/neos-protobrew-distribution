# PrototypeBrewery.io distribution of Neos CMS
[![Circle CI](https://circleci.com/gh/million12/neos-protobrew-distribution.svg?style=svg)](https://circleci.com/gh/million12/neos-protobrew-distribution)

Open-source version of [PrototypeBrewery.io](https://prototypebrewery.io/) project, 
the prototyping tool built on top of [Neos CMS](http://neos.io/) and 
[Zurb Foundation](http://foundation.zurb.com/) framework.


## Background

This is a **Prototype Brewery distribution** of Neos CMS. It is based on Neos CMS
*Base distribution**, but with [M12.Foundation](https://github.com/million12/M12.Foundation)
plugin installed (and its dependencies). M12.Foundation aims to implement
all Foundation components, in the best possible way, inside Neos CMS.

This work uses [Docker](https://docker.com/) containers. It will launch 
one container with MariaDB database and another container with Nginx/PHP 
to serve Neos CMS. Therefore some familiarity with Docker is desired.

## Usage

#### Short version

* map `DOCKER_IP dev.neos-protobrew.local neos-protobrew.local` in your `/etc/hosts` file
* clone this repository and run:  
```
docker-compose pull
docker-compose up
```
* Once you see `success: nginx entered RUNNING state` in the console, 
go to [http://dev.neos-protobrew.local:8899/](http://dev.neos-protobrew.local:8899) 
in your browser. PrototypeBrewery.io edition of Neos CMS is ready!

#### Long version

+ You will need a machine with Docker daemon to run Docker containers.
+ Add line with `DOCKER_IP dev.neos-protobrew.local neos-protobrew.local` 
  to `/etc/hosts` file on your machine. 
+ You will need [Docker Compose](https://docs.docker.com/compose/) installed 
  on your machine, arguably the simplest tool to orchestrate running multiple 
  Docker containers. Follow the documentation there.
+ Clone this repository (optionally: just grab 
  [docker-compose.yml](docker-compose.yml) file from here).
+ Run `docker-compose pull` to pull the necessary containers from 
  [hub.docker.com](https://hub.docker.com/). Note: this might take 
  a little while (~1.4GB).
+ Run `docker-compose up`.  
  Wait till all containers are running. You will see messages about 
  setting up Neos CMS and at the end you will see something like 
  `success: nginx entered RUNNING state` and `success: php-fpm entered RUNNING state`.
+ Go to [http://dev.neos-protobrew.local:8899/neos](http://dev.neos-protobrew.local:8899/neos) 
  to login to Neos CMS back-end.  
  Use username: `admin`, password: `password` to log in.
+ The front-end page is available under 
  [http://dev.neos-protobrew.local:8899/](http://neos-typostrap:8899). 
  Caveat: **It'is blank** by default until you add and publish some content 
  there.

At any time you can stop containers by pressing CTRL+C. Later on simply 
run `docker-compose up` again to continue from where you left it.

### Development

Docker Compose starts SSH container on port :5555.  
Run `ssh -p 5555 www@DOCKER_IP` and you are inside.  
The code resides inside `~/neos-protobrew` directory. You can use this access 
to run `./flow` tool and edit/change/upload files via SFTP. 

Authorisation to this SSH is possible via your public key. 
Set `IMPORT_GITHUB_PUB_KEYS` in `docker-compose.yml` to your GitHub username
and your key will be imported from there automatically (using public GitHub API).


## Author(s)

* Marcin Ryzycki marcin@m12.io  
* Samuel Ryzycki samuel@m12.io

---

**Sponsored by** [PrototypeBrewery.io - the new prototyping tool](http://prototypebrewery.io/) 
for building fully interactive prototypes of your website or web app. Built on top of 
Neos CMS and Zurb Foundation framework.
