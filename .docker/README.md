# Application

### Build
```
docker build --no-cache -f .docker/Dockerfile --target development . --tag=livescore/livescore-ecosystem-satellite:latest-development-debug-enetpulse

docker build --no-cache -f .docker/Dockerfile --target staging . --tag=llivescore/livescore-ecosystem-satellite:latest-staging-debug-enetpulse

docker build --no-cache -f .docker/Dockerfile --target release . --tag=livescore/livescore-ecosystem-satellite:latest-enetpulse
```

```
docker image prune -a
```
