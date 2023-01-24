# BeyondTrustPRAWebTools
Beyond Trust Privileged Remote Access Web Tools uses the BeyondTrust PRA API to extend the functionality of the core product

## Sessions.php

Sessions.php makes an API call to your BeyondTrust PRA appliance and returns all sessions from the past 7 days in an HTML table. Active sessions are listed first followed by all completed sessions. The query takes some time to complete so this should be cached server side. I do this using a cron job to output the results into a static html file.

### Setup

The following properties need to be set in order for this script to work:

$tokenUrl = "https://<SERVER>.beyondtrustcloud.com/oauth2/token";
$BasicAuth = "<CLIENT_ID>:<CLIENT_SECRET>";
$APIRequest = "https://<SERVER>.beyondtrustcloud.com/api/reporting?generate_report=AccessSession&start_time=".$reportFrom."&duration=0";

Replace <SERVER> with your cloud hosted appliance server name. If you're using a self-hosted appliance then you'll need to replace the full URL instead of just the subdomain.

Replace <CLIENT_ID> and <CLIENT_SECRET> with the API key generated on your appliance.

### API Key Permissions

For the tools currently supported in this package your API key will need the following permissions:
  * Reporting API: Access Sessions

 ### Cron job for sessions.php
 
 ```
 # Example cron job - change paths to match your environment
 # To avoid an empty web page for 10 seconds while the report updates, it creates a temporary sessions.html file then moves it to a file named 'sessions'
 # Feel free to rename the temp sessions.html file and sessions file to anything you wish for your end users
 * * * * * /usr/bin/curl http://localhost/sessions.php > /var/www/html/sessions.html && mv /var/www/html/sessions.html  /var/www/html/sessions
 ```
