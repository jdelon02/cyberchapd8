## Date Group Module

### Introduction
 The intention of this module is to display start and end dates in a fancy
 way by combining them together.
> **Example:** you have a formatted date: May 01, 2016 with format (F d, Y)
  using this module you will receive next formatted dates combinations.<br>
  * May 01-03, 2016
  * May 05-June 06, 2016
  * August 27, 2016-May 14, 2017<br>
  Common parts like same month or same year are merged together. First date
  range occurs in same month and year, second in same year, and the last in
  two different years.

### Requirements
 - Datetime Range Core module
 - Drupal Core 8.2.0 or later

### Installation
 - Download module via composer using `composer require 'drupal/date_group'` command.
   Alternatively place this module directory in your modules folder like 'modules/contrib'
 - Enable the module

### Configuration
 - Select *Date Group* Format in Manage Display settings for entity type where you want to
   enable date grouping for a Date range field
 - Open formatter configuration and make needed adjustments
 - Save display settings

### Maintainers
 * Sorin Dediu - [sdstyles](http://drupal.org/user/1420228)
