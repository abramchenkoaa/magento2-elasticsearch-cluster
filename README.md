# Elasticsearch Cluster module for Magento 2

## Overview
The `Labofgood_ElasticsearchCluster` module enables managing Elasticsearch cluster connections from Magento 2 admin panel.

## Prerequisites
- PHP 8.1 or higher
- Magento 2.4.5

## Dependencies
composer.json includes:
```
        "magento/framework": "103.0.*",
        "magento/module-elasticsearch-7": "100.4.*"
```

## Installation Steps
Please follow the instructions:

- Run `composer require --dev labofgood/module-elasticsearch-cluster`
- Run `bin/magento setup:upgrade`

## Usage Guide

Please follow the instructions:
- Go to `Stores > Configuration > Catalog > Catalog > Catalog Search` and select `Elasticsearch 7 Cluster` as `Search Engine`
- Add list of Elasticsearch cluster nodes in `Cluster Hosts` field
- Test connection by clicking `Test Connection` button
- Save configuration
- Flush Magento cache
- Run `bin/magento indexer:reindex catalogsearch_fulltext` to reindex catalog search.

## Uninstallation
To uninstall the module, run: `bin/magento module:uninstall Labofgood_ElasticsearchCluster`

## Credits
- Anton Abramchenko <anton.abramchenko@labofgood.com>

## Licensing
Copyright © 2023 Anton Abramchenko. All rights reserved.\
This software is under the "3-Clause BSD License" license (see source). 
