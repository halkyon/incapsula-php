[![CircleCI](https://img.shields.io/circleci/project/github/halkyon/incapsula-php.svg?style=flat-square)](https://circleci.com/gh/halkyon/incapsula-php)
[![Code Quality](https://img.shields.io/scrutinizer/g/halkyon/incapsula-php.svg?style=flat-square)](https://scrutinizer-ci.com/g/silverstripe/silverstripe-realme)

# Incapsula PHP SDK

WORK IN PROGRESS! Some or all of this may end up changing rapidly.

## Introduction

This project contains a PHP SDK to use the Incapsula API programmatically, as well as providing
command line tools for executing various tasks against the Incapsula API.

Currently only a few commands are available. This is currently a work in progress.

## Installation

TODO

## Configuring credentials

You can define credentials either as environment variables `INCAPSULA_API_ID` and `INCAPSULA_API_KEY`,
or as a credential file located in the current user home directory `~/.incapsula/credentials`.

Order of preference is environment variables first, then ini file.

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
