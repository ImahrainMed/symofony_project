# Introduction
SQLI 2022 - Symfony workshop

The following steps will help you initiate and install the suitable development environment.

## Installation & building the env
Run the following command to download and build the containers: 
```
docker-compose up -d --build
```

## Stopping all containers
I personnaly use the following command to stop all running containers because I usually use the same ports and some of the containers remain up so i need to stop them before :)
```
docker container stop $(docker container ps -aq)
```
## Starting the env
Please be careful, if you always run docker-compose up -d you may lose all config added to the containers. So i advice to run the following command to prevent recreation : 
```
docker-compose up -d --no-recreate --remove-orphans
```

## Rebuilding after adding a container or a config
If you have edited  the Dockerfile files or  added some config  to docker-compose.yml (networking, ips ...), you need to recreate the container(s)

```
docker-compose up -d --force-recreate
```
