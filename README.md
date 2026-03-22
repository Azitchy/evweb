# EV Charging Mobile Application

Electric vehicle charging app with wallet-based payments. Built with **Flutter** (frontend) and **Laravel** (backend API).

## Project Structure

```
evweb/
├── backend/          # Laravel REST API
│   ├── app/
│   │   ├── Http/Controllers/Api/   # API Controllers
│   │   ├── Http/Middleware/         # Admin & Active User middleware
│   │   ├── Models/                  # Eloquent Models
│   │   └── Services/               # Business Logic (Wallet, Charging)
│   ├── database/migrations/        # Database schema
│   ├── routes/api.php              # API Routes
│   └── ...
├── frontend/         # Flutter Mobile App
│   ├── lib/
│   │   ├── models/                  # Data Models
│   │   ├── providers/               # State Management (Provider)
│   │   ├── screens/                 # UI Screens
│   │   └── services/               # API Service
│   └── ...
└── README.md
```

## Backend Setup (Laravel)

### Prerequisites
- PHP 8.2+
- Composer
- MySQL

### Installation

```bash
cd backend

# Install dependencies
composer install

# Configure environment
cp .env.example .env
php artisan key:generate

# Update .env with your database credentials:
# DB_DATABASE=ev_charging
# DB_USERNAME=root
# DB_PASSWORD=your_password

# Run migrations and seed
php artisan migrate --seed

# Install Sanctum (already in composer.json)
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# Start server
php artisan serve
```

### Default Seeded Data
| Role  | Email                 | Password   |
|-------|-----------------------|------------|
| Admin | admin@evcharging.com  | password   |
| User  | user@evcharging.com   | password   |

Default pricing: **₹7 per percentage unit**

## Frontend Setup (Flutter)

### Prerequisites
- Flutter 3.x+
- Dart 3.x+

### Installation

```bash
cd frontend

# Install dependencies
flutter pub get

# Update API base URL in lib/services/api_service.dart
# For Android Emulator: http://10.0.2.2:8000/api
# For iOS Simulator:    http://127.0.0.1:8000/api
# For Physical Device:  http://<your-ip>:8000/api

# Run the app
flutter run
```

## API Endpoints

### Authentication
| Method | Endpoint          | Description        |
|--------|-------------------|--------------------|
| POST   | /api/register     | User registration  |
| POST   | /api/login        | User login         |
| POST   | /api/logout       | Logout (auth)      |
| GET    | /api/user         | Get current user   |

### Profile
| Method | Endpoint                      | Description          |
|--------|-------------------------------|----------------------|
| GET    | /api/profile                  | Get profile + stats  |
| PUT    | /api/profile                  | Update profile       |
| GET    | /api/profile/charging-history | Charging history     |
| GET    | /api/profile/transactions     | Transaction history  |

### Wallet
| Method | Endpoint              | Description          |
|--------|-----------------------|----------------------|
| GET    | /api/wallet/balance   | Get wallet balance   |
| POST   | /api/wallet/add-money | Add money to wallet  |
| GET    | /api/wallet/transactions | Wallet transactions |

### Charging
| Method | Endpoint                      | Description           |
|--------|-------------------------------|-----------------------|
| POST   | /api/charging/start           | Start charging        |
| POST   | /api/charging/{id}/stop       | Stop charging         |
| GET    | /api/charging/active          | Get active session    |
| GET    | /api/charging/history         | Charging history      |

### Admin
| Method | Endpoint                              | Description              |
|--------|---------------------------------------|--------------------------|
| GET    | /api/admin/dashboard                  | Dashboard analytics      |
| GET    | /api/admin/users                      | List users               |
| PATCH  | /api/admin/users/{id}/toggle-status   | Activate/deactivate user |
| GET    | /api/admin/pricing                    | Get current pricing      |
| POST   | /api/admin/pricing                    | Update pricing           |
| GET    | /api/admin/transactions               | All transactions         |
| GET    | /api/admin/charging/active            | Active sessions          |
| GET    | /api/admin/charging/logs              | Charging logs            |
| GET    | /api/admin/reports/revenue            | Revenue report           |
| GET    | /api/admin/reports/user-activity      | User activity report     |

## Charging Cost Formula

```
Cost = Charged Percentage × Price Per Percentage
```

Example: 50% charged at ₹7/% = **₹350** (auto-deducted from wallet)