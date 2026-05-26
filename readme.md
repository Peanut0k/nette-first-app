# Combat Air Patrol - Military Aviation News & Discussion

## Installation

```bash
make
make composer-install
```

Then visit `http://localhost:9000` in your browser to see the flight line briefing.

It requires PHP version 8.0 or newer.

## Overview

Combat Air Patrol is a military aviation news and discussion platform where aviation enthusiasts, veterans, and professionals can share information about military aircraft, operations, and aviation technology. The platform features:

- Latest updates on military aircraft and aviation technology
- Discussion forums for aviation professionals and enthusiasts
- Mission reports and operational updates
- Technical specifications and performance data

## Features

- User authentication and registration
- Create, edit, and delete aviation news articles
- Commenting system for community discussion
- Responsive design optimized for all devices
- Modern military-themed interface

## Technology Stack

- PHP 8.0+
- Nette Framework
- MySQL/MariaDB database
- Bootstrap 5 for responsive design
- Latte templating engine

## Administration

To access administrative features, users must register and log in. Once authenticated, users can:

- Create new aviation news articles
- Edit existing articles
- Delete articles (with confirmation)
- Post comments on articles
- Edit their own comments

## Database Schema

The application uses a MySQL/MariaDB database with the following tables:

- `posts`: Stores aviation news articles and reports
- `comments`: Stores user comments on articles
- `users`: Stores registered user information

Sample data includes information about:
- F-22 Raptor stealth capabilities
- A-10 Thunderbolt II close air support
- C-130 Hercules tactical airlift
- AH-64 Apache attack helicopter

## Running the Application

```bash
# Start all services (foreground)
make up

# Start in background
make up-daemon

# Stop services
make down

# View error logs
make e

# Enter container shell
make b

# PHPStan static analysis
make ps
```

The application will be available at http://localhost:9000