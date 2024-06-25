# Jela Svijeta API

## Table of Contents

- [Getting Started](#getting-started)
- [Prerequisites](#prerequisites)
- [Dependencies](#dependencies)
- [Installation](#installation)
- [Usage](#usage)

## Getting Started

These instructions will help you set up the project on your local machine.

## Prerequisites

- PHP >= 8.0
- Composer

## Dependencies

- Laravel 11.x
- laravel-translatable 11.x

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/ivanak7899/jela-svijeta-api.git
   cd jela-svijeta-api
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3.  **Copy the '.env' file and configure your enviroment**
   ```bash
   cp .env.example .env
   ```
   
4.  **Generate an application key**
   ```bash
   php artisan key:generate
   ```

5.  **Run the database migrations**
   ```bash
   php artisan migrate
   ```

6.  **Seed the database**
   ```bash
   php artisan db:seed
   ```

## Usage

Start the Laravel development server:
    ```bash
    php artisan serve
    ```

The API will be available at http://127.0.0.1:8000.
