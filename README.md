# BeyondTrustPRAWebTools
Beyond Trust Privileged Remote Access Web Tools uses the BeyondTrust PRA API to extend the functionality of the core product

# Sessions.php

## Setup

The following properties need to be set in order for this script to work:

$tokenUrl = "https://<SERVER>.beyondtrustcloud.com/oauth2/token";
$BasicAuth = "<CLIENT_ID>:<CLIENT_SECRET>";

Replace <SERVER> with your cloud hosted appliance server name. If you're using a self-hosted appliance then you'll need to replace the full URL instead of just the subdomain.

Replace <CLIENT_ID> and <CLIENT_SECRET> with the API key generated on your appliance.

## API Key Permissions

For the tools currently supported in this package your API key will need the following permissions:
  * Reporting API: Access Sessions
