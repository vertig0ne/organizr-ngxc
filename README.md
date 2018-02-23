# NGXConfigurator

Plugin for Organizr v2 that configures nginx location blocks based on the tabs and settings in Organizr

## Installation

### Manual Installation

    git clone https://github.com/vertig0ne/organizr-ngxc
    cd organizr-ngxc
    nano install.sh // Update the ORGANIZR_PATH to where your Organizr is installed. No trailing slash.
    chmod +x install.sh
    sudo ./install.sh

Then go to your organizr settings page, in the plugins tab, you will find it inactive. Enable it. Go to the settings page. Configure.
Please verify that once you have saved your first configuration you add the following line to your nginx `server {}` block

    include $ORG_DB_PATH/ngxc.conf;

Please remember to replace `$ORG_DB_PATH` for the full path of your org db, the core of this application saves files next to it as they are not supposed to be exposed to the outside world.

### Docker Installation

The letsorg container (also created by me) is pre-configured to use [linuxserver/letsencrypt](https://github.com/linuxserver/docker-letsencrypt) as its base, install organizr, install organizr-ngxc and do the hard tasks for you. Check out the configuration there, we would recommend during the installation part of organizr to keep the db path as `/config` as it's  the config i'm using so will be the most "supported".

Check out the docker at [vertig0ne/letsorg](https://hub.docker.com/r/vertig0ne/letsorg)

## Things to note:
So some notes to be prepared for.

Organizr v2 introduces groups for permissions, so you can have minimum permissions levels on services and be able to adjust users accordingly. This allows for levels of configuration of nginx. So if you were to change the permissions level on a tab inside Organizr, this plugin would make it so nginx is the same.

Nginx reload each time the configuration file is saved may be necessary. Will be looking into options for that in the future.


    location ~ /auth-(.*) {
        internal;
        rewrite ^ /api/?v1/auth&group=$1;
    }
    
This is how the nginx authentication works. In the same way as Organizr v1 worked with a few minor changes. Only one authentication block is necessary now. As we can call this via `auth_request /auth-0`. The number is the `group_id` for the group with the lowest permission allowed to access.