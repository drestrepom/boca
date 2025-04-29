# BOCA Project - Modern UI Enhancement

## Overview

BOCA (BOCA Online Contest Administrator) is a system designed for running programming contests. This version includes UI enhancements for a more modern, responsive interface.

## Key UI Improvements

- Modernized the contest configuration form with a card-based design
- Responsive layout that works on multiple screen sizes
- Consistent styling with amber/brown color theme
- Improved form field organization and alignment
- Modern buttons and inputs

## Requirements

- Docker and Docker Compose
- Modern web browser

## Getting Started with Docker

### Installation

1. Clone the repository:

```bash
git clone https://github.com/yourusername/boca.git
cd boca
```

2. Build and start the Docker containers:

```bash
docker-compose build
docker-compose up -d
```

This will start the following containers:

- BOCA web application (PHP + Apache)
- MySQL database
- PHPMyAdmin (optional, for database management)

### Accessing the Application

1. Once the containers are running, open your browser and navigate to:

```
http://localhost:8080/
```

or whatever port you've configured in your docker-compose.yml.

2. Log in with your credentials:
   - Default admin user: admin
   - Default password: (check the documentation or docker logs for initial password)

3. To see the modernized contest configuration form:
   - Navigate to `System` in the main menu
   - Select `Contest` from the dropdown
   - You'll see the new card-based design with improved layout

### Docker Management

Some useful Docker commands:

```bash
# View running containers
docker ps

# View container logs
docker logs boca-web

# Stop the containers
docker-compose down

# Restart containers
docker-compose restart

# Remove all containers and volumes (caution: this will delete your data)
docker-compose down -v
```

## Viewing the UI Changes

The main UI changes can be found in:

1. **Contest Configuration Page**:
   - Path: `src/system/contest.php`
   - Features responsive grid layout, modern form controls, and consistent styling
   - Works in both states: when selecting a contest and when editing contest details

2. Design Features:
   - Card layout with shadow effects
   - Amber/brown color scheme
   - Responsive grid that adapts to screen size
   - Improved form field alignment
   - Modern button styling
   - Consistent spacing and typography

## Development with Docker

When developing with Docker:

1. You can mount your local source code directory to the container for live editing:

```yaml
# In docker-compose.yml
volumes:
  - ./src:/var/www/html
```

2. Changes to CSS and PHP files will be immediately reflected in the browser.

3. To execute commands inside the container:

```bash
docker exec -it boca-web bash
```

## Debugging

- View Docker logs to debug issues:

```bash
docker logs boca-web
```

- Access the database through PHPMyAdmin at:

```
http://localhost:8080/ (or your configured port)
```

## Issues and Support

If you encounter any issues with the UI enhancements or Docker setup, please:

1. Check Docker logs for any errors
2. Verify your Docker and Docker Compose versions are up to date
3. Ensure all required ports are available on your system
4. Submit an issue on the project repository with details about your environment
