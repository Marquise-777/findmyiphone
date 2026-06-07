# Database Schema Analysis & Documentation

## Project: FindMyiPhone - Inventory Management System

Generated: 2026-06-07

---

## 📋 Executive Summary

This database manages an **inventory and order management system** for iPhone sales/inventory with:

- **19 total tables** (including Laravel framework tables)
- **2 many-to-many relationships** (product-category, product-color)
- **Cascade delete constraints** for data integrity
- **Hierarchical ownership**: Orders → Order Items → Product Units

---

## 🔍 Critical Schema Facts

### Migration Evolution Timeline

The schema evolved through these key changes:

1. **Initial Setup** (Feb 2026): Core tables (users, products, categories, colors, orders, brands)
2. **Junction Tables** (Feb 2026): Moved from direct FK to many-to-many (category_product, color_product)
3. **Customer Separation** (Apr 2026): Created dedicated customers table (was embedded in orders)
4. **Supplier Addition** (May 2026): Added suppliers table for cost tracking
5. **Final Normalization** (May 2026): Completed supplier integration with product_units

### Important Deprecations & Changes

- ⚠️ **OLD**: products.category_id & products.color_id (removed)
- ✅ **NEW**: category_product & color_product junction tables
- ⚠️ **OLD**: orders.customer_name, customer_contact, customer_address
- ✅ **NEW**: orders.customer_id (foreign key to customers table)

---

## 📊 Table Groups & Their Purpose

### Group 1: Authentication & Sessions (Laravel Framework)

```
users
  ├── password_reset_tokens (1:1 reference)
  └── sessions (1:many)
```

- **users**: Core Laravel user table with authentication
- **password_reset_tokens**: Token storage for password resets (email-keyed)
- **sessions**: Session management for authenticated users

### Group 2: Cache & Job Queue (Laravel Framework)

```
cache
cache_locks
jobs
  ├── job_batches
  └── failed_jobs
```

- **cache** & **cache_locks**: Application cache storage
- **jobs**: Queued jobs for async processing
- **job_batches**: Batch job grouping
- **failed_jobs**: Failed job tracking

### Group 3: Master Data (Lookup Tables)

```
brands (1:many → products)
categories (many:many → products via category_product)
colors (many:many → products via color_product)
```

- **brands**: Product brand/manufacturer reference
- **categories**: Product category/type reference (many-to-many)
- **colors**: Available color options (many-to-many)

### Group 4: Core Business Entities

```
customers (1:many → orders)
suppliers (1:many → product_units)
products (1:many → product_units, order_items)
```

- **customers**: Customer information and contact details
- **suppliers**: Supplier/vendor information
- **products**: Product master data

### Group 5: Product Inventory

```
product_units (child of products)
  - Individual unit tracking with IMEI
  - Links to: products, suppliers
```

- Tracks individual iPhone units by IMEI
- Each unit belongs to ONE product
- Tracks sold status and cost price per supplier

### Group 6: Order Management

```
orders (parent)
  └── order_items (child)
      ├── product_id (references products)
      └── product_unit_id (references specific units)
```

- Hierarchical: Order contains multiple OrderItems
- Each OrderItem references a specific ProductUnit
- Cascade delete: delete order → delete order_items

---

## 🗂️ Complete Table Reference

### 1. **users** - Authentication

| Field             | Type      | Constraints        | Purpose                      |
| ----------------- | --------- | ------------------ | ---------------------------- |
| id                | int       | PK, Auto-increment | Primary key                  |
| name              | varchar   | Not null           | User full name               |
| email             | varchar   | Unique, Not null   | User email                   |
| email_verified_at | timestamp | Nullable           | Email verification timestamp |
| password          | varchar   | Not null           | Hashed password              |
| remember_token    | varchar   | Nullable           | "Remember me" token          |
| created_at        | timestamp | Not null           | Creation timestamp           |
| updated_at        | timestamp | Not null           | Last update timestamp        |

### 2. **password_reset_tokens** - Password Recovery

| Field      | Type      | Constraints | Purpose             |
| ---------- | --------- | ----------- | ------------------- |
| email      | varchar   | PK          | Email being reset   |
| token      | varchar   | Not null    | Reset token         |
| created_at | timestamp | Nullable    | Token creation time |

### 3. **sessions** - Session Management

| Field         | Type        | Constraints                   | Purpose                 |
| ------------- | ----------- | ----------------------------- | ----------------------- |
| id            | varchar     | PK                            | Session ID              |
| user_id       | int         | FK (users), Nullable, Indexed | Logged-in user          |
| ip_address    | varchar(45) | Nullable                      | Client IP address       |
| user_agent    | text        | Nullable                      | Browser user agent      |
| payload       | longtext    | Not null                      | Serialized session data |
| last_activity | int         | Indexed                       | Last activity timestamp |

### 4. **cache** - Application Cache

| Field      | Type       | Constraints | Purpose                   |
| ---------- | ---------- | ----------- | ------------------------- |
| key        | varchar    | PK          | Cache key                 |
| value      | mediumtext | Not null    | Cached value              |
| expiration | int        | Indexed     | Expiration Unix timestamp |

### 5. **cache_locks** - Cache Locking

| Field      | Type    | Constraints | Purpose                        |
| ---------- | ------- | ----------- | ------------------------------ |
| key        | varchar | PK          | Lock key                       |
| owner      | varchar | Not null    | Lock owner identifier          |
| expiration | int     | Indexed     | Lock expiration Unix timestamp |

### 6. **jobs** - Async Job Queue

| Field        | Type             | Constraints        | Purpose                  |
| ------------ | ---------------- | ------------------ | ------------------------ |
| id           | int              | PK, Auto-increment | Job ID                   |
| queue        | varchar          | Indexed            | Queue name               |
| payload      | longtext         | Not null           | Serialized job data      |
| attempts     | unsigned tinyint | Not null           | Number of attempts       |
| reserved_at  | unsigned int     | Nullable           | Reservation timestamp    |
| available_at | unsigned int     | Not null           | Next available timestamp |
| created_at   | unsigned int     | Not null           | Creation Unix timestamp  |

### 7. **job_batches** - Job Batch Tracking

| Field          | Type       | Constraints | Purpose                   |
| -------------- | ---------- | ----------- | ------------------------- |
| id             | varchar    | PK          | Batch ID                  |
| name           | varchar    | Not null    | Batch name                |
| total_jobs     | int        | Not null    | Total jobs in batch       |
| pending_jobs   | int        | Not null    | Pending job count         |
| failed_jobs    | int        | Not null    | Failed job count          |
| failed_job_ids | longtext   | Not null    | JSON of failed IDs        |
| options        | mediumtext | Nullable    | Batch options             |
| cancelled_at   | int        | Nullable    | Cancellation timestamp    |
| created_at     | int        | Not null    | Creation Unix timestamp   |
| finished_at    | int        | Nullable    | Completion Unix timestamp |

### 8. **failed_jobs** - Failed Job Logging

| Field      | Type      | Constraints        | Purpose               |
| ---------- | --------- | ------------------ | --------------------- |
| id         | int       | PK, Auto-increment | Record ID             |
| uuid       | varchar   | Unique             | Job UUID              |
| connection | text      | Not null           | Queue connection name |
| queue      | text      | Not null           | Queue name            |
| payload    | longtext  | Not null           | Job payload           |
| exception  | longtext  | Not null           | Exception message     |
| failed_at  | timestamp | Default: NOW       | Failure timestamp     |

### 9. **brands** - Product Brands

| Field      | Type      | Constraints        | Purpose               |
| ---------- | --------- | ------------------ | --------------------- |
| id         | int       | PK, Auto-increment | Brand ID              |
| name       | varchar   | Unique, Not null   | Brand name            |
| created_at | timestamp | Not null           | Creation timestamp    |
| updated_at | timestamp | Not null           | Last update timestamp |

### 10. **categories** - Product Categories

| Field      | Type      | Constraints        | Purpose               |
| ---------- | --------- | ------------------ | --------------------- |
| id         | int       | PK, Auto-increment | Category ID           |
| name       | varchar   | Unique, Not null   | Category name         |
| created_at | timestamp | Not null           | Creation timestamp    |
| updated_at | timestamp | Not null           | Last update timestamp |

### 11. **colors** - Product Colors

| Field      | Type      | Constraints        | Purpose               |
| ---------- | --------- | ------------------ | --------------------- |
| id         | int       | PK, Auto-increment | Color ID              |
| name       | varchar   | Unique, Not null   | Color name            |
| created_at | timestamp | Not null           | Creation timestamp    |
| updated_at | timestamp | Not null           | Last update timestamp |

### 12. **suppliers** - Supplier Information

| Field          | Type      | Constraints        | Purpose               |
| -------------- | --------- | ------------------ | --------------------- |
| id             | int       | PK, Auto-increment | Supplier ID           |
| name           | varchar   | Not null           | Supplier company name |
| contact_person | varchar   | Nullable           | Contact person name   |
| phone          | varchar   | Nullable           | Contact phone         |
| email          | varchar   | Nullable           | Contact email         |
| address        | text      | Nullable           | Supplier address      |
| created_at     | timestamp | Not null           | Creation timestamp    |
| updated_at     | timestamp | Not null           | Last update timestamp |

### 13. **customers** - Customer Information

| Field      | Type      | Constraints        | Purpose               |
| ---------- | --------- | ------------------ | --------------------- |
| id         | int       | PK, Auto-increment | Customer ID           |
| name       | varchar   | Not null           | Customer name         |
| phone      | varchar   | Nullable           | Customer phone        |
| email      | varchar   | Nullable           | Customer email        |
| created_at | timestamp | Not null           | Creation timestamp    |
| updated_at | timestamp | Not null           | Last update timestamp |

### 14. **products** - Product Master Data

| Field           | Type          | Constraints           | Purpose               |
| --------------- | ------------- | --------------------- | --------------------- |
| id              | int           | PK, Auto-increment    | Product ID            |
| name            | varchar       | Not null              | Product name          |
| sku             | varchar       | Unique, Not null      | Stock Keeping Unit    |
| brand_id        | int           | FK (brands), Nullable | Product brand         |
| selling_price   | decimal(10,2) | Not null              | Retail selling price  |
| available_stock | int           | Default: 0            | Available quantity    |
| is_active       | boolean       | Default: true         | Product active status |
| created_at      | timestamp     | Not null              | Creation timestamp    |
| updated_at      | timestamp     | Not null              | Last update timestamp |

**Note**: Category and Color are now linked via junction tables (not direct FKs)

### 15. **product_units** - Individual Product Units

| Field       | Type          | Constraints              | Purpose                  |
| ----------- | ------------- | ------------------------ | ------------------------ |
| id          | int           | PK, Auto-increment       | Unit ID                  |
| product_id  | int           | FK (products), Cascade   | Parent product           |
| imei        | varchar       | Unique, Not null         | IMEI/Serial number       |
| is_sold     | boolean       | Default: false           | Sale status              |
| sold_at     | timestamp     | Nullable                 | Sale date                |
| supplier_id | int           | FK (suppliers), Nullable | Supplier of this unit    |
| cost_price  | decimal(12,2) | Nullable                 | Cost price from supplier |
| created_at  | timestamp     | Not null                 | Creation timestamp       |
| updated_at  | timestamp     | Not null                 | Last update timestamp    |

### 16. **category_product** - Product-Category Junction

| Field        | Type                      | Constraints              | Purpose             |
| ------------ | ------------------------- | ------------------------ | ------------------- |
| id           | int                       | PK, Auto-increment       | Record ID           |
| product_id   | int                       | FK (products), Cascade   | Product reference   |
| category_id  | int                       | FK (categories), Cascade | Category reference  |
| Unique Index | (product_id, category_id) | Composite unique         | Prevents duplicates |

### 17. **color_product** - Product-Color Junction

| Field        | Type                   | Constraints            | Purpose             |
| ------------ | ---------------------- | ---------------------- | ------------------- |
| id           | int                    | PK, Auto-increment     | Record ID           |
| product_id   | int                    | FK (products), Cascade | Product reference   |
| color_id     | int                    | FK (colors), Cascade   | Color reference     |
| Unique Index | (product_id, color_id) | Composite unique       | Prevents duplicates |

### 18. **orders** - Sales Orders

| Field            | Type          | Constraints              | Purpose               |
| ---------------- | ------------- | ------------------------ | --------------------- |
| id               | int           | PK, Auto-increment       | Order ID              |
| invoice_number   | varchar       | Unique, Not null         | Invoice reference     |
| customer_id      | int           | FK (customers), Nullable | Customer (new)        |
| customer_name    | varchar       | Nullable                 | Legacy customer name  |
| customer_contact | varchar       | Nullable                 | Legacy contact info   |
| customer_address | varchar       | Nullable                 | Legacy address        |
| subtotal         | decimal(10,2) | Not null                 | Sum of items          |
| discount_percent | decimal(5,2)  | Default: 0               | Discount percentage   |
| discount_amount  | decimal(12,2) | Default: 0               | Calculated discount   |
| discount         | decimal(10,2) | Default: 0               | Legacy discount field |
| tax              | decimal(10,2) | Default: 0               | Tax amount            |
| due              | decimal(10,2) | Default: 0               | Amount due            |
| paid             | decimal(10,2) | Default: 0               | Amount paid           |
| total            | decimal(10,2) | Not null                 | Final total           |
| payment_method   | varchar       | Nullable                 | Payment method used   |
| created_at       | timestamp     | Not null                 | Order creation date   |
| updated_at       | timestamp     | Not null                 | Last update timestamp |

**Important**: Use `customer_id` for new orders. Legacy fields (customer_name, customer_contact, customer_address) are deprecated.

### 19. **order_items** - Order Line Items

| Field           | Type          | Constraints                 | Purpose                     |
| --------------- | ------------- | --------------------------- | --------------------------- |
| id              | int           | PK, Auto-increment          | Item ID                     |
| order_id        | int           | FK (orders), Cascade        | Parent order                |
| product_id      | int           | FK (products), Cascade      | Product sold                |
| product_unit_id | int           | FK (product_units), Cascade | Specific unit sold          |
| price           | decimal(10,2) | Not null                    | Selling price at order time |
| cost_price      | decimal(12,2) | Default: 0                  | Cost price for accounting   |
| created_at      | timestamp     | Not null                    | Creation timestamp          |
| updated_at      | timestamp     | Not null                    | Last update timestamp       |

---

## 🔗 Relationship Mapping

### Foreign Key Relationships

| From Table       | From Column     | To Table      | Constraint                  |
| ---------------- | --------------- | ------------- | --------------------------- |
| sessions         | user_id         | users         | Nullable FK                 |
| products         | brand_id        | brands        | Nullable FK                 |
| product_units    | product_id      | products      | Cascade Delete              |
| product_units    | supplier_id     | suppliers     | Nullable FK, Cascade Delete |
| category_product | product_id      | products      | Cascade Delete              |
| category_product | category_id     | categories    | Cascade Delete              |
| color_product    | product_id      | products      | Cascade Delete              |
| color_product    | color_id        | colors        | Cascade Delete              |
| orders           | customer_id     | customers     | Nullable FK, Cascade Delete |
| order_items      | order_id        | orders        | Cascade Delete              |
| order_items      | product_id      | products      | Cascade Delete              |
| order_items      | product_unit_id | product_units | Cascade Delete              |

### Relationship Types

| Type               | Example                              | Cardinality                   |
| ------------------ | ------------------------------------ | ----------------------------- |
| 1:N (One-to-Many)  | customers (1) → orders (N)           | One customer has many orders  |
| 1:N                | products (1) → product_units (N)     | One product has many units    |
| N:N (Many-to-Many) | products (N) ↔ categories (N)        | Via category_product junction |
| N:N                | products (N) ↔ colors (N)            | Via color_product junction    |
| Hierarchical       | orders → order_items → product_units | 3-level hierarchy             |

---

## ⚙️ Cascade Delete Behavior

When you delete a record, cascading deletes propagate:

```
DELETE products with id=5
  ↓ Cascades to:
    - product_units (all units of this product)
    - category_product (all categories of this product)
    - color_product (all colors of this product)
    - order_items (all order items referencing this product)

DELETE orders with id=100
  ↓ Cascades to:
    - order_items (all items in this order)

DELETE order_items with id=500
  ↓ Cascades to:
    (no child tables)
```

**⚠️ WARNING**: Be careful with deletes as they cascade. Always verify before deleting.

---

## 📝 Important Business Logic Notes

### Product Inventory Management

- **available_stock** in products table tracks total available units
- **product_units** tracks individual units with IMEI
- **is_sold** flag on product_units indicates if unit was sold
- Relationship: multiple product_units → one product

### Order Processing

- Orders link to specific **customer_id** (not embedded data)
- Each order contains multiple **order_items**
- Each item references a specific **product_unit** (the actual unit sold)
- Price snapshot stored in order_items (not current product price)
- Cost price tracked for accounting/profit calculation

### Supplier Tracking

- Suppliers track vendors who supply product_units
- Each unit can have different cost_price from different suppliers
- cost_price in order_items used for profit calculations

### Product Categories & Colors

- Products can have **multiple categories** (via category_product)
- Products can have **multiple colors** (via color_product)
- Previously stored directly in products table (deprecated)
- Many-to-many design allows flexible categorization

### Discounting

- Two discount tracking mechanisms in orders:
    - **discount_percent** + **discount_amount** (new approach)
    - **discount** field (legacy, keep for backwards compatibility)
- Recommend using discount_percent & discount_amount for new code

---

## 🔐 Data Integrity Constraints

### Unique Constraints

- `users.email` - One email per user
- `password_reset_tokens.email` - Primary key
- `sessions.id` - Session ID unique
- `cache.key` - Cache key unique
- `cache_locks.key` - Lock key unique
- `brands.name` - Unique brand names
- `categories.name` - Unique category names
- `colors.name` - Unique color names
- `products.sku` - One SKU per product
- `product_units.imei` - One IMEI per unit (unique serial)
- `orders.invoice_number` - One invoice per order
- `category_product(product_id, category_id)` - Composite unique
- `color_product(product_id, color_id)` - Composite unique

### Not Null Constraints

- Core identifying fields must be NOT NULL
- Foreign keys are mostly NULLABLE to allow optional relationships
- Monetary values are NOT NULL

### Defaults

- Booleans default to false/0
- Stock defaults to 0
- Discount/tax/prices default to 0
- is_active defaults to true

---

## 📊 Index Strategy

### Indexed Columns (for query performance)

- `sessions(user_id)` - User session lookups
- `sessions(last_activity)` - Session cleanup queries
- `cache(expiration)` - Cache expiration queries
- `cache_locks(expiration)` - Lock cleanup queries
- `jobs(queue)` - Queue processing

### Implicit Indexes (via foreign keys)

- All foreign key columns are automatically indexed

---

## 🎯 Query Examples

### Find All Orders for a Customer

```sql
SELECT o.* FROM orders o
WHERE o.customer_id = ?
ORDER BY o.created_at DESC;
```

### Find All Units Sold in an Order

```sql
SELECT pu.imei, pu.cost_price, oi.price, (oi.price - pu.cost_price) as profit
FROM order_items oi
JOIN product_units pu ON oi.product_unit_id = pu.id
WHERE oi.order_id = ?;
```

### Find All Categories for a Product

```sql
SELECT c.* FROM categories c
JOIN category_product cp ON c.id = cp.category_id
WHERE cp.product_id = ?;
```

### Calculate Product Profitability

```sql
SELECT
  p.name,
  p.sku,
  COUNT(pu.id) as total_units,
  SUM(CASE WHEN pu.is_sold = 1 THEN 1 ELSE 0 END) as sold_units,
  SUM(CASE WHEN pu.is_sold = 0 THEN 1 ELSE 0 END) as available_units
FROM products p
LEFT JOIN product_units pu ON p.id = pu.product_id
GROUP BY p.id
ORDER BY sold_units DESC;
```

---

## ✅ Schema Validation Checklist

- [x] All tables have primary keys
- [x] Foreign keys use CASCADE DELETE where appropriate
- [x] Nullable FKs for optional relationships
- [x] Unique constraints on identifiers (email, SKU, IMEI)
- [x] Timestamps (created_at, updated_at) on business tables
- [x] Composite unique constraints on junction tables
- [x] Cascade delete doesn't create orphans
- [x] Decimal precision (10,2) for standard prices, (12,2) for cost tracking
- [x] Deprecated fields marked but retained for migration safety

---

## 🚀 Next Steps / Recommendations

1. **Create indexes** on frequently queried columns (customer lookups, product SKU searches)
2. **Archive old orders** - consider partitioning order_items by year
3. **Audit logging** - add audit trail for order modifications
4. **Soft deletes** - consider soft delete pattern for audit trail
5. **Denormalization** - consider caching frequently calculated totals in orders
6. **Reporting views** - create materialized views for financial reports

---

**Document Version**: 1.0
**Database Type**: MySQL
**Laravel Version**: Inferred from migration patterns (11.x)
**Last Updated**: 2026-06-07
