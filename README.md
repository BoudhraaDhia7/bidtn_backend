# BID-TN API

## Requirements

-   [PHP] 8.3.3 (php version: php -v)
-   [composer] v2

## Installation

### 1 - Clone project

```Shell
git clone https://boudhraad-admin@bitbucket.org/anypli/bidtn-api.git
```

### 2 - Configuration

-   Duplicate .env.example and rename it to .env
-   Generate app key

```shell
php artisan key:generate
```

### 2-1 Update .env file

Ensure you update the `.env` file with the following keys and values:

| Key                     | Description              | Value (example)                                       |
| ----------------------- | ------------------------ | ----------------------------------------------------- |
| `APP_NAME`              | Application name         | `BID-TN`                                              |
| `APP_ENV`               | Application environment  | `local`                                               |
| `APP_KEY`               | Application key          | `base64:las1Xi9tU8ZVq3EqGXEDk/lMcttVOiLyB/xDsOrASKg=` |
| `APP_DEBUG`             | Application debug mode   | `true`                                                |
| `APP_URL`               | Application URL          | `http://localhost:8000`                               |
| `APP_PORT`              | Application port         | `8000`                                                |
| `FRONT_URL`             | Frontend URL             | `http://localhost:5174`                               |
| `LOG_CHANNEL`           | Log channel              | `stack`                                               |
| `DB_CONNECTION`         | Database connection type | `mysql`                                               |
| `DB_HOST`               | Database host            | `mysql`                                               |
| `DB_PORT`               | Database port            | `3306`                                                |
| `DB_DATABASE`           | Database name            | `bidtn`                                               |
| `DB_USERNAME`           | Database username        | `sail`                                                |
| `DB_PASSWORD`           | Database password        | `password`                                            |
| `CACHE_DRIVER`          | Cache driver             | `redis`                                               |
| `QUEUE_CONNECTION`      | Queue connection         | `sync`                                                |
| `SESSION_DRIVER`        | Session driver           | `redis`                                               |
| `REDIS_HOST`            | Redis host               | `redis`                                               |
| `REDIS_PASSWORD`        | Redis password           | `null`                                                |
| `REDIS_PORT`            | Redis port               | `6379`                                                |
| `MAIL_MAILER`           | Mailer                   | `smtp`                                                |
| `MAIL_HOST`             | Mail host                | `smtp.gmail.com`                                      |
| `MAIL_PORT`             | Mail port                | `587`                                                 |
| `MAIL_USERNAME`         | Mail username            | `YOUR_USER_NAME`                                      |
| `MAIL_PASSWORD`         | Mail password            | `YOUR_API_KEY`                                        |
| `MAIL_ENCRYPTION`       | Mail encryption          | `tls`                                                 |
| `MAIL_FROM_ADDRESS`     | Mail from address        | `YOUR_USER_NAME`                                      |
| `MAIL_FROM_NAME`        | Mail from name           | `${APP_NAME}`                                         |
| `JWT_SECRET`            | JWT secret               | `YOUR_JWT_SECRET`                                     |
| `JWT_TTL`               | JWT time-to-live         | `60`                                                  |
| `REVERB_APP_ID`         | Reverb app ID            | `283142`                                              |
| `REVERB_APP_KEY`        | Reverb app key           | `wncx0rshcupt5nl12xtj`                                |
| `REVERB_APP_SECRET`     | Reverb app secret        | `nu5zjikikeqwsy20hp3k`                                |
| `REVERB_HOST`           | Reverb host              | `localhost`                                           |
| `REVERB_PORT`           | Reverb port              | `8080`                                                |
| `REVERB_SCHEME`         | Reverb scheme            | `http`                                                |
| `BROADCAST_DRIVER`      | Broadcast driver         | `reverb`                                              |
| `VITE_REVERB_APP_KEY`   | Vite Reverb app key      | `${REVERB_APP_KEY}`                                   |
| `VITE_REVERB_HOST`      | Vite Reverb host         | `${REVERB_HOST}`                                      |
| `VITE_REVERB_PORT`      | Vite Reverb port         | `${REVERB_PORT}`                                      |
| `VITE_REVERB_SCHEME`    | Vite Reverb scheme       | `${REVERB_SCHEME}`                                    |
| `STRIPE_SECRET_KEY`     | Stripe secret key        | `YOUR_STRIPE_SECRET_KEY`                              |
| `STRIPE_PUBLIC_KEY`     | Stripe public key        | `YOUR_STRIPE_PUBLIC_KEY`                              |
| `STRIPE_WEBHOOK_SECRET` | Stripe webhook secret    | `YOUR_STRIPE_WEBHOOK_SECRET_KEY`                      |

### 2-2 - Install project dependencies

```Shell
composer install
```

### 3 - Install sail dependencies

```Shell
php artisan sail:install
```

**When prompt choose mysql**

### 4 - Start laravel sail

```Shell
./vendor/bin/sail up
```

**Optional:** You can configure a shell alias for sail instead of repeatedly typing `vendor/bin/sail`

```shell
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```

To make sure this is always available, you may add this to your shell configuration file in your home directory, such
as ~/.zshrc or ~/.bashrc, and then restart your shell.
Now you can execute Sail commands by simply typing `sail`

**Ex:**

```shell
sail up
```

### 5 - Run the migrations with seeders

```Shell
php artisan migrate --seed
```

### 6 - Setup crone jobs

```Shell
php artisan schedule:run
```
