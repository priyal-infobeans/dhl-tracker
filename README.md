# dhl-tracker
# This module is used to find DHL locations.
# Depandancies to run this module  
PHP Version    - 8.1
My SQL Version - 8.0
Drupal Version - 10.0

# Steps to be followed after Drupal installation-
1. Install module named 'Track location'.
2. Install JSON:API, Rest UI, RESTful Web Services and Serialization Modules.
3. Enable Rest API by locating Configuration->Web Services->REST. Search for content api and click on Enable button.
4. Click on Method **GET** **POST** and Accepted Request formats **json** and Authenticator providers **basic auth** and **cookies** and click on Save Configuration.
5. Refresh cache under configuration->performance.
6. URL- '/search-location' to access tracking form.
7. URL- '/api/content' API data
