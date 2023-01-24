# Batch Create

Batch create is designed for quickly creating sites and/or usernames or adding users to an existing site in batches by uploading a CSV file. Slightly tweaked and updated from the [WPMU Dev Batch Create plugin](https://github.com/wpmudev/batch-create/).

## Differences from the WPMU plugin
- This works on modern WordPress and PHP as of writing this (WP 6.1.1 and PHP 8)
- It does not send email on user registration or site creation.
- It uses email address, not username as the unique identifier for looking up Users. If an email is already in use, it will find the username that corresponds with that email and proceed.
- I've removed XLS support and streamlined the instructions page.
- The CSV template is tweaked a bit.

## Installation
Download this repository as a zip file, and upload it using the WordPress plugins page.
