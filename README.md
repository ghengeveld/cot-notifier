# Notifier

This is a port of the Notifier plugin by Neocrome from LDU/Seditio.

## Features

* Subscribe to a topic by email or PM
* Sends email/PM when there's a new post

### New in v2.0

* Automatic subscriptions, can be set in user profile
* Uses sed_auth instead of usr['level']
* Fully localisable (includes EN and NL language files)

## Installation

* Extract and upload files
* Backup your database
* Run sed_notifier.sql on your database
* Go to admin > plugins > notifier > install all
* Check for any missing tags