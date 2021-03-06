# File upload exercise with websockets
 
**Note:** Being just an exercise, validation and/or security was not considered.

## Installation and Configuration:
1. Clone or download the repo:
`git clone https://github.com/sica07/ws-exercise.git`

2. Go to the downloaded folder:
`cd ws-exercise`

#### With docker:
3. Run:
 `docker-compose up --build`
 
4. Identify the ID of the docker container 
`docker ps`
and log into it:
`docker exec -it ws_srv_1 bash`

5. Once inside the docker container start the websocket server:
`php srv/server.php`

#### Without docker:
3. In the _config.js_ and _srv/config.php_ files the **server_ip** and **port** 
   constants to match your environment.
   
4. Start the websocket server:
`php srv/server.php`

## How to use:
1. Load in two separate windows the following urls:
   - http://200.0.100.10/index.html
   - http://200.0.100.10/client.html
   
   _(Of course, if not using docker, you should replace the IP with the one from the config files.)_

2. In the **index.html** browser window upload an image. As a result:
  * in the **index.html** you should see the _download_ link for the image.
  * in the **client.html** window the image should load

