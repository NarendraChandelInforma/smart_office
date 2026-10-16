# Smart Office

A PHP and MySQL team task planner for managing daily tasks, priorities, progress, and team members.

## Requirements

- PHP with the MySQLi extension
- MySQL or MariaDB
- Apache (for example, XAMPP)

## Local setup with XAMPP

1. Place this project in the XAMPP `htdocs` directory.
2. Create a MySQL database named `task_planner`.
3. Configure the database connection using the environment variables below, or use the local defaults (`localhost`, `root`, no password).
4. Start Apache and MySQL, then open `http://localhost/smart_office/`.

The project does not currently include a database schema or migrations. The `tasks` and `team_members` tables must be created before the application can use the database.

## Database configuration

The app reads these environment variables and falls back to XAMPP-friendly local defaults:

| Variable | Local default |
| --- | --- |
| `DB_HOST` | `localhost` |
| `DB_USER` | `root` |
| `DB_PASS` | (empty) |
| `DB_NAME` | `task_planner` |

Set these in your PHP hosting environment; do not commit production credentials.

## GitHub and deployment

This repository publishes the application source. GitHub Pages only hosts static sites and cannot execute PHP or connect to MySQL. To run the full application publicly, deploy it to a PHP-capable host and configure its database and environment variables.