---
name: ci4-best-practices
description: Expert guidance for CodeIgniter 4 development, focusing on MVC architecture, thin controllers, fat models (or services), validation, and standard coding conventions. Use when adding new features, refactoring code, or ensuring code quality in CI4.
---

# CodeIgniter 4 Best Practices

This skill provides expert guidance for developing and refactoring CodeIgniter 4 applications.

## Core Principles

### 1. Thin Controllers, Fat Models/Services
Controllers should only handle the flow of the application:
- Parse and validate input data.
- Call Service or Model methods.
- Return the appropriate view or response.

Example of a thin controller:
```php
public function store()
{
    if (!$this->validate('ticket')) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $ticketService = service('ticket');
    $ticketId = $ticketService->createTicket($this->request->getPost());

    return redirect()->to('/tickets')->with('success', 'Ticket created.');
}
```

### 2. Use Services for Business Logic
When logic involves multiple models or external APIs, use a **Service**. Create them in `app/Services`.

### 3. Model-Level Validation
Define validation rules in your Models to ensure data integrity at the lowest level.
```php
protected $validationRules = [
    'cliente_id' => 'required|is_natural_no_zero',
    'descripcion' => 'required|min_length[10]',
];
```

### 4. Use Entities
Use CI4 Entities to represent your data as objects rather than arrays. This allows you to add helper methods to your data objects.
```php
// In Model
protected $returnType = \App\Entities\Ticket::class;
```

### 5. Dependency Injection
Prefer using `service()` or constructor injection instead of manual instantiation with `new`.

### 6. Standard Coding Standards (PSR-12)
- Follow PSR-12 for PHP coding standards.
- Use camelCase for methods and variables.
- Use PascalCase for classes.

## Specific Task Redlines

- **No business logic in Controllers**: If it takes more than 20 lines to save an item, it belongs in a Service.
- **Avoid Global state**: Use `service('request')` or `$this->request` instead of `$_POST` or `$_GET`.
- **Consistent Response**: Always return `ResponseInterface` objects from controllers.
