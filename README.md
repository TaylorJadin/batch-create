# Batch Create

Create hundred or thousands of blogs and users automatically by simply uploading a csv text file. Slightly tweaked and updated from the [WPMU Dev Batch Create plugin](https://github.com/wpmudev/batch-create/).

Differences from the defunct WPMU plugin:
- This works on modern WordPress and PHP as of writing this.
- It does not send email on user registration or site creation.
- It uses email address, not username as the primary unique identifier. If an email already corresponds to an account it will proceed using that existing account and username instead of trying to create a new account.
- I've removed out XLS file support and streamlined the instructions page.
- The CSV template is tweaked slightly.
