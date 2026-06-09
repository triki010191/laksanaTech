# Database Rules

Naming Convention

Tables:
plural snake_case

Examples:

users
projects
portfolio_items
service_orders

Columns:

id
uuid
created_at
updated_at

All business entities must use UUID.

Soft Delete required for:
- orders
- users
- projects