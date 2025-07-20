# Hotel Booking API Documentation

## Base URL
```
http://your-domain.com/api/v1
```

## Authentication
API menggunakan Laravel Sanctum untuk autentikasi. Setelah login, gunakan token yang diterima di header:
```
Authorization: Bearer {your-token}
```

## Response Format
Semua response menggunakan format JSON dengan struktur:
```json
{
    "success": true|false,
    "message": "Response message",
    "data": {}, // Data response
    "errors": {} // Validation errors (jika ada)
}
```

## Endpoints

### Authentication

#### Register Customer
```
POST /auth/register
```
**Body:**
```json
{
    "full_name": "John Doe",
    "email": "john@example.com",
    "phone": "081234567890",
    "password": "password123",
    "password_confirmation": "password123"
}
```

#### Login
```
POST /auth/login
```
**Body:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

#### Logout
```
POST /auth/logout
Authorization: Bearer {token}
```

#### Get Current User
```
GET /auth/me
Authorization: Bearer {token}
```

### Hotels

#### Get All Hotels
```
GET /hotels?page=1&per_page=10&category=luxury&city=Jakarta&sort_by=rating&sort_order=desc
```

#### Get Hotel Detail
```
GET /hotels/{id}
```

#### Get Hotel Rooms
```
GET /hotels/{id}/rooms
```

#### Search Hotels
```
GET /hotels/search?query=Grand Hotel&check_in=2024-01-15&check_out=2024-01-18&guests=2
```

#### Get Hotel Categories
```
GET /hotels/categories
```

### Customer Profile

#### Get Profile
```
GET /customer/profile
Authorization: Bearer {token}
```

#### Update Profile
```
PUT /customer/profile
Authorization: Bearer {token}
```
**Body:**
```json
{
    "full_name": "John Doe Updated",
    "email": "john.updated@example.com",
    "phone": "081234567890",
    "current_password": "oldpassword",
    "password": "newpassword",
    "password_confirmation": "newpassword"
}
```

#### Upload Avatar
```
POST /customer/upload-avatar
Authorization: Bearer {token}
Content-Type: multipart/form-data
```
**Body:**
```
avatar: [image file]
```

### Bookings

#### Get All Bookings
```
GET /bookings?status=confirmed&page=1&per_page=10
Authorization: Bearer {token}
```

#### Create Booking
```
POST /bookings
Authorization: Bearer {token}
```
**Body:**
```json
{
    "hotel_id": 1,
    "room_id": 1,
    "guest_name": "John Doe",
    "guest_email": "john@example.com",
    "guest_phone": "081234567890",
    "check_in_date": "2024-01-15",
    "check_out_date": "2024-01-18",
    "guests": 2,
    "payment_method": "credit_card",
    "special_requests": "Late check-in please"
}
```

#### Get Booking Detail
```
GET /bookings/{id}
Authorization: Bearer {token}
```

#### Cancel Booking
```
PUT /bookings/{id}/cancel
Authorization: Bearer {token}
```

#### Get Booking History
```
GET /bookings/history
Authorization: Bearer {token}
```

## Error Codes

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error

## Rate Limiting
API dibatasi 60 requests per menit per IP address.

## Testing
Gunakan tools seperti Postman atau Insomnia untuk testing API endpoints.