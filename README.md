# Laravel + Dockerized Typesense Hybrid Search Demo

A lightweight demonstration of full-text keyword search, facet filtering, and semantic vector search (via Reciprocal Rank Fusion) built using **Laravel**, **Typesense**, **Docker**, **Bootstrap 5**, and **jQuery**.

---

## Tech Stack
* **Backend:** Laravel 11 / PHP 8.2+
* **Search Engine:** Typesense (Dockerized)
* **Frontend:** Blade Templates, Bootstrap 5, jQuery (AJAX)

---

## Prerequisites
Ensure your local machine has the following installed:
* PHP (>= 8.2) & Composer
* Docker & Docker Compose

---

## Setup & Installation Guide

### 1. Clone or Open the Project
Navigate into your project root directory in the terminal:
```bash
cd typesense-demo
```

### 2. Install PHP Dependencies
Install the required Composer packages, including the official Typesense PHP SDK:
```bash
composer install
```

### 3. Configure Environment Variables
Copy the default environment file and generate your application key:
```bash
cp .env.example .env
php artisan key:generate
```

Open your `.env` file and configure your Typesense connection settings:
```env
TYPESENSE_HOST=localhost
TYPESENSE_PORT=8108
TYPESENSE_PROTOCOL=http
TYPESENSE_API_KEY=xyz
```

### 4. Start Typesense via Docker Compose
Spin up the Typesense search engine container in detached mode:
```bash
docker-compose up -d
```

Verify that the Typesense container is running and healthy:
```bash
curl http://localhost:8108/health
```
*(Expected output: `{"ok":true}`)*

### 5. Setup the Collection Schema & Seed Demo Data
Run the custom Artisan command to provision your Typesense collection schema and index sample documents:
```bash
php artisan typesense:setup
```

---

## Running the Demo

1. Start the local Laravel development server:
   ```bash
   php artisan serve
   ```
2. Open your web browser and navigate to:
   ```text
   http://127.0.0.1:8000/search
   ```

---

## Features Tested in this Demo
* **Instant Type-ahead Search:** Live debounced querying as you type text keywords.
* **Facet Filtering:** Restrict search results dynamically by selecting categories (e.g., Backend, Frontend, Search).
* **Hybrid Vector Search:** Toggle the hybrid switch to blend keyword text matching with semantic vector scoring.