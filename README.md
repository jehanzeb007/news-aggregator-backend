# News Aggregator


---

## Getting Started with Docker (Laravel Sail)

This project is configured for **Docker development** using [Laravel Sail](https://laravel.com/docs/12.x/sail).

### Prerequisites

- [Docker](https://www.docker.com/products/docker-desktop) installed on your machine
- [Docker Compose](https://docs.docker.com/compose/) (included with recent Docker Desktop)

### Quick Start

1. **Clone this repository:**
    ```bash
    cd news-aggregator
    ```

2. **Copy `.env.example` to `.env` and set your app key:**
    ```bash
    cp .env.example .env
    ```

3. **Start the application using Sail:**
    ```bash
    ./vendor/bin/sail up -d
    ```

   The first build may take a few minutes.

4. **(If you are starting from source or have not installed dependencies):**
    ```bash
    ./vendor/bin/sail composer install
    ./vendor/bin/sail artisan key:generate
    ```

5. **Run migrations:**
    ```bash
    ./vendor/bin/sail artisan migrate
    ```

6. **Access your app:**

   Visit  [http://localhost:8081](http://localhost:8081) in your browser (depending on your configured port).

---

## Common Docker/Sail Commands

| Task                  | Command                                     |
|-----------------------|---------------------------------------------|
| Start containers      | `./vendor/bin/sail up -d`                   |
| Stop containers       | `./vendor/bin/sail down`                    |
| Run migrations        | `./vendor/bin/sail artisan migrate`         |


For convenience, you can add a Sail alias to your shell:
```bash
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
