# KodeCreators Blog API

Laravel 12 REST API for Blog Management with role-based access control.

## Tech Stack

- **Framework:** Laravel 12
- **Auth:** Sanctum (Bearer Token)
- **Database:** MySQL
- **Authorization:** Policies & Middleware (no Spatie)

## Roles

| Role | Blogs | Comments | Likes |
|------|-------|----------|-------|
| **Admin** | Create, List, Edit, Delete | List only | ✗ |
| **Maintainer** | List, Edit, Delete | List, Delete any | ✗ |
| **User** | List, Create | List, Create, Delete own | ✓ Like/Unlike |

## Setup

```bash
# 1. Clone
git clone https://github.com/Shibu967/kodecreators-blog-api.git
cd kodecreators-blog-api

# 2. Install
composer install

# 3. Environment
cp .env.example .env
php artisan key:generate

# 4. Database - update .env with your MySQL credentials
DB_DATABASE=kodecreators_blog_api
DB_USERNAME=root
DB_PASSWORD=your_password

# 5. Run migrations & seed
php artisan migrate --seed

# 6. Storage link (for image uploads)
php artisan storage:link

# 7. Start server
php artisan serve
```

## Seeded Data

- **500 Users** (Admin, Maintainer, User roles)
- **1000 Blogs**
- **10,000 Comments** (10 per blog)
- **10,000 Likes** (10 per blog)

### Default Login

```
Email: admin@example.com
Password: password
```

## API Endpoints

| # | Method | Endpoint | Description |
|---|--------|----------|-------------|
| 1 | POST | `/api/v1/login` | Login & get token |
| 2 | POST | `/api/v1/users` | Create user |
| 3 | PUT | `/api/v1/user/profile` | Edit own name |
| 4 | POST | `/api/v1/blogs` | Create blog |
| 5 | GET | `/api/v1/blogs` | List blogs |
| 6 | PUT | `/api/v1/blogs/{id}` | Edit blog |
| 7 | DELETE | `/api/v1/blogs/{id}` | Delete blog |
| 8 | POST | `/api/v1/blogs/{id}/comments` | Add comment |
| 9 | GET | `/api/v1/blogs/{id}/comments` | List comments |
| 10 | DELETE | `/api/v1/blogs/{id}/comments/{id}` | Delete comment |
| 11 | POST | `/api/v1/likes/toggle` | Like/Unlike (Blog or Comment) |

## Postman Collection

Import `KodeCreators_Blog_API.postman_collection.json` from the project root.

Set `base_url` variable to `http://localhost:8000/api` in Postman.
