# Rating System API Documentation

## Base URL
```
https://your-domain.com/api/v1
```

## Authentication
Most endpoints require Bearer token authentication using Laravel Sanctum.

## Endpoints

### 1. Submit Rating (POST /ratings)
**Authentication Required: Yes**

Submit a new rating for a service.

**Request:**
```json
{
    "transaction_id": "TXN123456",
    "service_type": "tour",
    "service_id": 1,
    "supplier_id": 5,
    "rating": 5,
    "comment": "Excellent service! Highly recommended.",
    "customer_name": "John Doe",
    "customer_email": "john@example.com"
}
```

**Response (201):**
```json
{
    "success": true,
    "message": "Rating submitted successfully",
    "data": {
        "id": 1,
        "transaction_id": "TXN123456",
        "service_type": "tour",
        "service_id": 1,
        "service_name": "Amazing City Tour",
        "rating": 5,
        "rating_text": "Excellent",
        "stars": "★★★★★",
        "comment": "Excellent service! Highly recommended.",
        "is_verified": true,
        "rated_at": "2024-01-15T10:30:00.000000Z",
        "rated_at_human": "2 hours ago",
        "customer": {
            "id": 10,
            "name": "John Doe",
            "email": "john@example.com"
        },
        "supplier": {
            "id": 5,
            "name": "Travel Company ABC",
            "email": "info@travelabc.com"
        }
    }
}
```

### 2. Get Supplier Ratings (GET /ratings/supplier/{supplier_id})
**Authentication Required: No**

Get all ratings for a specific supplier with statistics.

**Parameters:**
- `service_type` (optional): Filter by service type (tour, event, gift)
- `per_page` (optional): Items per page (max 50, default 15)

**Example:** `GET /ratings/supplier/5?service_type=tour&per_page=10`

**Response (200):**
```json
{
    "success": true,
    "data": {
        "supplier": {
            "id": 5,
            "name": "Travel Company ABC",
            "email": "info@travelabc.com"
        },
        "ratings": [
            {
                "id": 1,
                "transaction_id": "TXN123456",
                "service_type": "tour",
                "rating": 5,
                "rating_text": "Excellent",
                "stars": "★★★★★",
                "comment": "Amazing experience!",
                "rated_at": "2024-01-15T10:30:00.000000Z",
                "customer": {
                    "name": "John Doe"
                }
            }
        ],
        "statistics": {
            "average_rating": 4.5,
            "total_ratings": 25,
            "rating_distribution": {
                "1": 1,
                "2": 2,
                "3": 5,
                "4": 7,
                "5": 10
            }
        },
        "ratings_by_type": {
            "tour": 4.6,
            "event": 4.3,
            "gift": 4.7
        },
        "pagination": {
            "current_page": 1,
            "last_page": 3,
            "per_page": 10,
            "total": 25,
            "has_more_pages": true
        }
    }
}
```

### 3. Get Supplier Statistics (GET /ratings/supplier/{supplier_id}/stats)
**Authentication Required: No**

Get detailed statistics for a supplier.

**Parameters:**
- `service_type` (optional): Filter by service type

**Response (200):**
```json
{
    "success": true,
    "data": {
        "supplier_id": 5,
        "supplier_name": "Travel Company ABC",
        "average_rating": 4.5,
        "total_ratings": 25,
        "rating_distribution": {
            "1": 1,
            "2": 2,
            "3": 5,
            "4": 7,
            "5": 10
        },
        "ratings_by_service_type": {
            "tour": {
                "average": 4.6,
                "count": 15
            },
            "event": {
                "average": 4.3,
                "count": 5
            },
            "gift": {
                "average": 4.7,
                "count": 5
            }
        }
    }
}
```

### 4. Get Customer's Ratings (GET /ratings/my-ratings)
**Authentication Required: Yes**

Get all ratings submitted by the authenticated customer.

**Parameters:**
- `per_page` (optional): Items per page (max 50, default 15)

**Response (200):**
```json
{
    "success": true,
    "data": {
        "ratings": [
            {
                "id": 1,
                "transaction_id": "TXN123456",
                "service_type": "tour",
                "service_name": "Amazing City Tour",
                "rating": 5,
                "rating_text": "Excellent",
                "comment": "Great experience!",
                "is_verified": true,
                "rated_at": "2024-01-15T10:30:00.000000Z",
                "supplier": {
                    "id": 5,
                    "name": "Travel Company ABC",
                    "email": "info@travelabc.com"
                }
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 2,
            "per_page": 15,
            "total": 20,
            "has_more_pages": true
        }
    }
}
```

### 5. Check if Customer Can Rate (GET /ratings/can-rate)
**Authentication Required: Yes**

Check if a customer can rate a specific transaction.

**Parameters:**
- `transaction_id` (required): Transaction ID to check
- `customer_id` (optional): Customer ID (uses authenticated user if not provided)

**Example:** `GET /ratings/can-rate?transaction_id=TXN123456`

**Response (200):**
```json
{
    "success": true,
    "data": {
        "can_rate": true,
        "message": "Customer can submit rating for this transaction"
    }
}
```

### 6. Get Recent Ratings (GET /ratings/recent)
**Authentication Required: No**

Get recent ratings across all suppliers.

**Parameters:**
- `limit` (optional): Number of ratings to return (max 50, default 10)
- `service_type` (optional): Filter by service type

**Example:** `GET /ratings/recent?limit=20&service_type=tour`

**Response (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "service_type": "tour",
            "rating": 5,
            "rating_text": "Excellent",
            "comment": "Amazing experience!",
            "rated_at": "2024-01-15T10:30:00.000000Z",
            "customer": {
                "name": "John Doe"
            },
            "supplier": {
                "id": 5,
                "name": "Travel Company ABC"
            }
        }
    ]
}
```

### 7. Get Top Rated Suppliers (GET /ratings/top-suppliers)
**Authentication Required: No**

Get suppliers with highest ratings.

**Parameters:**
- `limit` (optional): Number of suppliers to return (max 50, default 10)
- `service_type` (optional): Filter by service type

**Response (200):**
```json
{
    "success": true,
    "data": [
        {
            "supplier_id": 5,
            "supplier_name": "Travel Company ABC",
            "supplier_email": "info@travelabc.com",
            "average_rating": 4.8,
            "total_ratings": 25
        }
    ]
}
```

## Error Responses

### Validation Error (422)
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "rating": ["The rating field is required."],
        "service_type": ["The selected service type is invalid."]
    }
}
```

### Already Rated (409)
```json
{
    "success": false,
    "message": "You have already rated this transaction."
}
```

### Service Not Found (404)
```json
{
    "success": false,
    "message": "Service or supplier not found."
}
```

### Unauthorized (401)
```json
{
    "success": false,
    "message": "Unauthenticated."
}
```

## Usage Examples

### JavaScript/Fetch
```javascript
// Submit a rating
const submitRating = async (ratingData) => {
    const response = await fetch('/api/v1/ratings', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        },
        body: JSON.stringify(ratingData)
    });
    
    return await response.json();
};

// Get supplier ratings
const getSupplierRatings = async (supplierId, page = 1) => {
    const response = await fetch(`/api/v1/ratings/supplier/${supplierId}?per_page=15&page=${page}`);
    return await response.json();
};
```

### cURL Examples
```bash
# Submit rating
curl -X POST "https://your-domain.com/api/v1/ratings" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "transaction_id": "TXN123456",
    "service_type": "tour",
    "service_id": 1,
    "supplier_id": 5,
    "rating": 5,
    "comment": "Excellent service!"
  }'

# Get supplier ratings
curl "https://your-domain.com/api/v1/ratings/supplier/5?service_type=tour&per_page=10"
```

## Service Types
- `tour` - Travel tours/trips
- `event` - Events/activities  
- `gift` - Gift services

## Rating Scale
- 1 = Very Poor
- 2 = Poor  
- 3 = Average
- 4 = Good
- 5 = Excellent
