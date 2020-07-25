## Using docker-compose

The first you should to change `.env.example` to `.env` and add your values.

This application provides a `docker-compose.yml` for use with
[docker-compose](https://docs.docker.com/compose/) it
uses the provided `Dockerfile` to build a docker image 
for the `application` container created with `docker-compose`.

Build and start the image and container using:

```bash
$ docker-compose up -d --build
```

Also, you need run commands such as `composer` in the container.  The container 
environment is named "web" so you will pass that value to 
`docker-compose run`:

```bash
$ docker-compose run web composer install
```

At this point, you can visit [http://localhost:8080](http://localhost:8080/) to see the site running.

```bash
Email: admin@test.gmail
Password: admin
```