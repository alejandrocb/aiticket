---
name: ci4-db-optimization
description: Best practices for database design, query optimization, and efficient data handling in CodeIgniter 4. Use when designing schemas, writing complex queries, or troubleshooting performance issues.
---

# CodeIgniter 4 Database & Optimization

This skill provides guidance for optimizing database interactions in CodeIgniter 4.

## Database Best Practices

### 1. Indexed Queries
Ensure all columns used in `WHERE`, `JOIN`, and `ORDER BY` clauses are indexed.
- Use Migrations to manage indexes.

### 2. Eager Loading vs. Lazy Loading
CI4 doesn't have a full ORM with eager loading by default (unlike Eloquent), so manually optimize joins:
- Avoid the N+1 problem by using `JOIN` instead of multiple `find()` calls in a loop.
- Use `select()` to retrieve only the necessary columns.

### 3. Query Caching
For expensive queries that don't change often, use the Cache service:
```php
if (! $data = cache('expensive_query_result')) {
    $data = $model->getExpensiveData();
    cache()->save('expensive_query_result', $data, 3600);
}
```

### 4. Use Database Transactions
For operations involving multiple table updates, use Transactions to ensure atomicity.
```php
$db->transStart();
$modelA->insert($data1);
$modelB->insert($data2);
$db->transComplete();

if ($db->transStatus() === false) {
    // Transaction failed
}
```

### 5. Efficient Pagination
Use CI4's built-in `pager` instead of manual `LIMIT` and `OFFSET` whenever possible.
```php
$data = [
    'users' => $model->paginate(10),
    'pager' => $model->pager,
];
```

### 6. Batch Operations
Use `insertBatch()` and `updateBatch()` for processing multiple records in a single query.

## Performance Redlines
- **No `SELECT *`**: Always specify columns.
- **Limit Join depth**: Avoid joining more than 5-6 tables in a single query if possible.
- **Avoid subqueries in WHERE**: Use `JOIN` instead of `WHERE id IN (SELECT id FROM ...)` for better performance in MySQL.
