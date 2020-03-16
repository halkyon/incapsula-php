[![CircleCI](https://img.shields.io/circleci/project/github/halkyon/incapsula-php/master.svg?style=flat-square)](https://circleci.com/gh/halkyon/incapsula-php)
[![Code Quality](https://img.shields.io/scrutinizer/g/halkyon/incapsula-php/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/halkyon/incapsula-php)

# Incapsula PHP SDK

## Introduction

This project contains a PHP SDK to use the Incapsula API programmatically, as well as providing
command line tools for executing various tasks against the Incapsula API.

Please see the [public API docs for Incapsula/Imperva](https://docs.imperva.com/bundle/cloud-application-security/page/api/api.htm)
for more information on how the API works.

## Installation

1. Clone the repo locally: `git clone git@github.com:halkyon/incapsula-php.git`.
2. Run `composer install` to install dependencies.
3. Configure your Incapsula API credentials (see below).
4. Test your credentials using the `./bin/incapsula sites:list` command
5. See a list of all commands you can run by calling `./bin/incapsula`

## Configuring credentials

You can define credentials either as environment variables `INCAPSULA_API_ID` and `INCAPSULA_API_KEY`,
or as a credential file located in the current user home directory `~/.incapsula/credentials`.

Order of preference is environment variables first, then ini file.

Note: Environment variables must be actually set as env vars, not just added in a `.env` file in the application root.

If you're confident about the security of your machine, you can define them on the command-line, for example:
`INCAPSULA_API_ID=ABC123 INCAPSULA_API_KEY=xyz789 ./bin/incapsula sites:list` 

Example ini file:
```
[default]
incapsula_api_id = 123
incapsula_api_key = 1234-5678
```

Additional profiles can be added by defining more `[section]`s in the file. These are then used
by passing the `--profile <name>` option to commands.

## Usage

Run `./bin/incapsula` to show a listing of all available commands.

Some commands have a `--json` argument which returns as JSON instead of output as a table.

## Example command usage

### List IP ranges

```
incapsula integration:ips
```

### List sites

```
incapsula sites:list
```

### List all cache rules for all sites

```
incapsula sites:listcacherules
```
### Upload custom certificate

```
incapsula customcertificate:upload <site-id> <certificate-path> <private-key-path>
```

## Example SDK usage

Use the sites API to enumerate all available sites, create a new site, upload a custom certitificate,
then remove what was created:

```php
$client = new Incapsula\Client();
$sitesApi = $client->sites();

$sites = $sitesApi->list();
foreach ($sites as $site) {
    var_dump($site['site_id']);
}

$site = $sitesApi->add([
    'domain' => 'mysite.com',
]);

var_dump($site['site_id']);

$sitesApi->uploadCustomCertificate($site['site_id'], '---- CERT ----', '---- KEY ----');
$sitesApi->removeCustomCertificate($site['site_id']);

$sitesApi->delete($site['site_id']);
```

Use the integration API to retrieve Incapsula IP ranges:

```php
$client = new Incapsula\Client();
$ips = $client->integration()->ips();
var_dump($ips['ipRanges']);
var_dump($ips['ipv6Ranges']);
```

## Contributing

Before making a pull request, please run the code syntax fixer to make sure the linter works: `vendor/bin/php-cs-fixer fix`.