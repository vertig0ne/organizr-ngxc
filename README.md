# NGXConfigurator
Plugin for Organizr v2 that configures nginx auth_request locations so levels can be set dynamically.

## Things to note:
So some notes to be prepared for.

Organizr v2 introduces groups for permissions, so you can have minimum permissions levels on things and be able to adjust users accordingly. This allows for levels of configuration of nginx. So if you was to change the permissions level on a tab inside Organizr, this plugin would make it so nginx is the same.

We make use of nginx variables by storing them in a seperate file. We manually include that file in our `server {}` block so the variables are available to each `location {}` block. We can give the appearance of changing the levels on the tab page and having them write to the configuration file.

Nginx reload each time the configuration file is saved may be necessary. Will be looking into options for that in the future.
