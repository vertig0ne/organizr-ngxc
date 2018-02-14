# NGXConfigurator
Plugin for Organizr v2 that configures nginx auth_request locations so levels can be set dynamically.

## Things to note:
So some notes to be prepared for.

Organizr v2 introduces groups for permissions, so you can have minimum permissions levels on services and be able to adjust users accordingly. This plugin allows for dynamic levels fot authorization using nginx. So if you were to change the permissions level on a tab inside Organizr, this plugin would make it so the reverse proxy has the same level.

We use nginx variables to do so, these are stored in a seperate file. We manually include this file in our `server {}` block so the variables are available to each `location {}` block. 

Reloading Nginx may be necessary after changeing permissions. Will be looking into options for preventing that in the future.


    location ~ /auth-(.*) {
        internal;
        rewrite ^ /api/?v1/auth&group=$1;
    }
    
This is how the Nginx authentication works. The same way as Organizr v1 worked with a few minor changes. Only one authentication block is necessary now. As we can call this via `auth_request /auth-0`. The number is the `group_id` for the group with the lowest permission allowed to access.

In order to use this you will need to update your location blocks to use something along the lines of `auth_request /auth-$SONARR_AUTH` 

For ease of use, at this time, it follows the pattern '$TABNAME_AUTH' where 'TABNAME' is the name of the tab in uppercase letters and any spaces need to be underscores. This may or may not be changed in the future.
