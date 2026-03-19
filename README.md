# MyAdmin ModernBill Plugin

[![Build Status](https://github.com/detain/myadmin-modernbill-plugin/actions/workflows/tests.yml/badge.svg)](https://github.com/detain/myadmin-modernbill-plugin/actions)
[![Latest Stable Version](https://poser.pugx.org/detain/myadmin-modernbill-plugin/version)](https://packagist.org/packages/detain/myadmin-modernbill-plugin)
[![Total Downloads](https://poser.pugx.org/detain/myadmin-modernbill-plugin/downloads)](https://packagist.org/packages/detain/myadmin-modernbill-plugin)
[![License](https://poser.pugx.org/detain/myadmin-modernbill-plugin/license)](https://packagist.org/packages/detain/myadmin-modernbill-plugin)

A MyAdmin plugin that provides integration with the ModernBill client billing system. This package enables management of ModernBill clients, invoices, and packages directly through the MyAdmin administration panel and hooks into the Symfony EventDispatcher for seamless plugin lifecycle handling.

## Features

- View and search ModernBill client records
- Browse and inspect invoices with full payment history
- List active client packages with pricing details
- Admin ACL-based access control for billing operations
- Symfony EventDispatcher integration for plugin hooks

## Requirements

- PHP >= 5.0
- ext-soap
- Symfony EventDispatcher ^5.0

## Installation

Install via Composer:

```sh
composer require detain/myadmin-modernbill-plugin
```

## Running Tests

```sh
composer install
vendor/bin/phpunit
```

## License

This package is licensed under the [LGPL-2.1](https://opensource.org/licenses/LGPL-2.1) license.
