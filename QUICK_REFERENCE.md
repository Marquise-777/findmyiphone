# Database Quick Reference Guide

## 📌 At a Glance

**Total Tables**: 19
**Business Tables**: 10 (customers, suppliers, orders, products, etc.)
**Framework Tables**: 9 (users, sessions, jobs, cache, etc.)

---

## 🎯 Core Business Tables (What You Need to Know)

```
customers
├── orders (1:many)
│   ├── order_items (1:many)
│   │   ├── products
│   │   └── product_units
│   │       └── suppliers
│   ├── discounts & pricing
│   └── payment_method
│
products
├── product_units (1:many)
│   ├── imei (unique identifier)
│   ├── is_sold (boolean)
│   └── cost_price
│
├── categories (many:many via junction)
├── colors (many:many via junction)
└── brands (many:1)

suppliers
└── product_units (1:many)
```

---

## 🔑 Key Tables & Fields

### **products**

| Field           | Type    | Notes                    |
| --------------- | ------- | ------------------------ |
| id              | int     | Primary key              |
| name            | string  | Product name             |
| sku             | string  | **UNIQUE** - Stock code  |
| brand_id        | FK      | Optional brand reference |
| selling_price   | decimal | Retail price             |
| available_stock | int     | Total units available    |
| is_active       | boolean | Active/inactive flag     |

**Relations**:

- Many product_units (each unit is an individual iPhone)
- Many categories (via category_product junction)
- Many colors (via color_product junction)

### **product_units**

| Field       | Type      | Notes                           |
| ----------- | --------- | ------------------------------- |
| id          | int       | Primary key                     |
| product_id  | FK        | Parent product (cascade delete) |
| imei        | string    | **UNIQUE** - Serial number      |
| is_sold     | boolean   | Tracks if sold                  |
| sold_at     | timestamp | When it was sold                |
| supplier_id | FK        | Who supplied this unit          |
| cost_price  | decimal   | Cost from supplier              |

**Key Point**: Each individual iPhone unit has one record here with its IMEI

### **orders**

| Field            | Type    | Notes                   |
| ---------------- | ------- | ----------------------- |
| id               | int     | Primary key             |
| invoice_number   | string  | **UNIQUE** - Invoice ID |
| customer_id      | FK      | ✅ NEW - Use this       |
| subtotal         | decimal | Sum of items            |
| discount_percent | decimal | % discount              |
| discount_amount  | decimal | $ discount              |
| tax              | decimal | Tax amount              |
| total            | decimal | Final amount            |
| payment_method   | string  | Cash, card, etc         |

**Deprecated Fields** (avoid using):

- customer_name, customer_contact, customer_address

### **order_items**

| Field           | Type    | Notes                         |
| --------------- | ------- | ----------------------------- |
| id              | int     | Primary key                   |
| order_id        | FK      | Which order                   |
| product_id      | FK      | Which product                 |
| product_unit_id | FK      | Which specific unit/iPhone    |
| price           | decimal | Selling price at time of sale |
| cost_price      | decimal | Cost for accounting           |

**Key Point**: Links specific iPhone unit to the order

### **customers**

| Field | Type   | Notes         |
| ----- | ------ | ------------- |
| id    | int    | Primary key   |
| name  | string | Customer name |
| phone | string | Optional      |
| email | string | Optional      |

### **suppliers**

| Field          | Type   | Notes         |
| -------------- | ------ | ------------- |
| id             | int    | Primary key   |
| name           | string | Supplier name |
| contact_person | string | Optional      |
| phone          | string | Optional      |
| email          | string | Optional      |
| address        | text   | Optional      |

### **categories & colors & brands**

| Field      | Type     | Notes                  |
| ---------- | -------- | ---------------------- |
| id         | int      | Primary key            |
| name       | string   | **UNIQUE** - Name      |
| timestamps | datetime | created_at, updated_at |

---

## ⚡ Common Queries

### Get all orders for a customer

```sql
SELECT * FROM orders WHERE customer_id = 5 ORDER BY created_at DESC;
```

### Get all items in an order with product details

```sql
SELECT
  oi.id,
  p.name,
  pu.imei,
  oi.price,
  oi.cost_price,
  (oi.price - oi.cost_price) as profit
FROM order_items oi
JOIN products p ON oi.product_id = p.id
JOIN product_units pu ON oi.product_unit_id = pu.id
WHERE oi.order_id = 100;
```

### Find unsold inventory

```sql
SELECT
  p.name,
  p.sku,
  COUNT(pu.id) as count
FROM products p
JOIN product_units pu ON p.id = pu.product_id
WHERE pu.is_sold = false
GROUP BY p.id;
```

### Get all categories for a product

```sql
SELECT c.* FROM categories c
JOIN category_product cp ON c.id = cp.category_id
WHERE cp.product_id = 5;
```

### Calculate profit by supplier

```sql
SELECT
  s.name,
  COUNT(pu.id) as units_sold,
  SUM(CASE WHEN oi.price IS NOT NULL THEN oi.price - pu.cost_price ELSE 0 END) as total_profit
FROM suppliers s
LEFT JOIN product_units pu ON s.id = pu.supplier_id
LEFT JOIN order_items oi ON pu.id = oi.product_unit_id
GROUP BY s.id;
```

---

## ⚠️ Important Rules

### Cascade Deletes

- Delete product → deletes all product_units → deletes from orders
- Delete order → deletes all order_items
- Be careful! Verify before deleting.

### Many-to-Many Logic

- **Categories & Colors**: Use junction tables, not direct FKs

    ```sql
    -- Add category to product
    INSERT INTO category_product (product_id, category_id) VALUES (5, 3);

    -- Remove category from product
    DELETE FROM category_product WHERE product_id = 5 AND category_id = 3;
    ```

### Unique Constraints

- IMEI must be unique (each iPhone unit is unique)
- SKU must be unique (product code is unique)
- Invoice number must be unique
- Brand/Category/Color names must be unique

### Nullable Foreign Keys

- customer_id in orders - order can exist without customer reference
- brand_id in products - product can exist without brand
- supplier_id in product_units - unit can exist without supplier
- (All others are required - cascade delete enforced)

---

## 🗂️ Framework Tables (Auto-generated, ignore mostly)

| Table                 | Purpose         | Keep?                 |
| --------------------- | --------------- | --------------------- |
| users                 | Authentication  | Yes - necessary       |
| sessions              | User sessions   | Yes - necessary       |
| password_reset_tokens | Password resets | Yes - necessary       |
| cache                 | App caching     | Yes - housekeeping    |
| cache_locks           | Cache locking   | Yes - housekeeping    |
| jobs                  | Background jobs | Yes - if using queues |
| job_batches           | Job grouping    | Yes - if using queues |
| failed_jobs           | Job failures    | Yes - debugging       |

These are created/maintained by Laravel. Don't manually modify.

---

## 🔄 Schema Evolution History

| Date     | Change              | Migration                        |
| -------- | ------------------- | -------------------------------- |
| Feb 2026 | Initial schema      | Create main tables               |
| Feb 2026 | Many-to-many setup  | category_product, color_product  |
| Apr 2026 | Customer separation | Create customers table           |
| Apr 2026 | Order normalization | Add customer_id to orders        |
| May 2026 | Supplier addition   | Create suppliers table           |
| May 2026 | Supplier linking    | Add supplier_id to product_units |

---

## 📋 Validation Checklist

When working with this DB:

- [ ] All products have SKU (unique identifier)
- [ ] All product_units have IMEI (unique identifier)
- [ ] All orders have invoice_number (unique identifier)
- [ ] customer_id is used (not customer_name/contact/address)
- [ ] Categories/Colors added via junction tables
- [ ] Cost prices tracked in order_items
- [ ] Supplier info tracked for each unit
- [ ] Order totals match sum of items + tax - discounts

---

## 🆘 Troubleshooting

**Q: Can't find a product's category?**
A: Use the `category_product` junction table:

```sql
SELECT c.* FROM categories c
JOIN category_product cp ON c.id = cp.category_id
WHERE cp.product_id = ?;
```

**Q: Why do orders have both customer_id and customer_name?**
A: Legacy migration. Use `customer_id` for new orders. Old orders may still have customer_name.

**Q: How do I track profit per order?**
A: Use `order_items.price - order_items.cost_price` (if cost_price is populated)

**Q: Can one product_unit belong to multiple products?**
A: No, each unit belongs to exactly ONE product (CASCADE DELETE enforced)

**Q: Can I delete an order?**
A: Yes, it will cascade delete all order_items. Be careful!

---

**This is your living schema documentation. Update this when schema changes.**
