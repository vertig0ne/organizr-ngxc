# NGXConfigurator
Plugin for Organizr v2 that configures nginx auth_request locations so permission levels can be set dynamically.

## Things to note:
So some notes to be prepared for.

Organizr v2 introduces groups for permissions, so you can have minimum permissions levels on services and be able to adjust users accordingly. This allows for levels of configuration of nginx. So if you was to change the permissions level on a tab inside Organizr, this plugin would make it so nginx is the same.

We make use of nginx variables by storing them in a seperate file. We manually include that file in our `server {}` block so the variables are available to each `location {}` block. We can give the appearance of changing the levels on the tab page and having them write to the configuration file.

Nginx reload each time the configuration file is saved may be necessary. Will be looking into options for that in the future.


    location ~ /auth-(.*) {
        internal;
        rewrite ^ /api/?v1/auth&group=$1;
    }
    
This is how the nginx authentication works. In the same way as Organizr v1 worked with a few minor changes. Only one authentication block is necessary now. As we can call this via `auth_request /auth-0`. The number is the `group_id` for the group with the lowest permission allowed to access.

In order to use this you will need to of course update your location blocks to use something along the lines of `auth_request /auth-$SONARR_AUTH` 

For ease of use, at this time, it follows the pattern '$TABNAME_AUTH' where 'TABNAME' is the name of the tab in uppercase letters and any spaces need to be underscores. This may or may not be changed in the future.
