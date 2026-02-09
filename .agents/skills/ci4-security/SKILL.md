---
name: ci4-security
description: Security hardening for CodeIgniter 4 applications. Covers CSRF, XSS prevention, SQL injection safeguards, secure session management, and environment configuration. Use when reviewing code for security vulnerabilities or configuring application security settings.
---

# CodeIgniter 4 Security Hardening

This skill provides guidance for securing CodeIgniter 4 applications.

## Key Security Areas

### 1. CSRF Protection
Ensure CSRF protection is enabled in `app/Config/Filters.php`.
Use `<?= csrf_field() ?>` in every form.

### 2. XSS Prevention
Always use the `esc()` helper when outputting data to views.
```php
// Bad
echo $user['name'];

// Good
echo esc($user['name']);
echo esc($user['bio'], 'html');
```

### 3. SQL Injection Safeguards
- Always use the Query Builder (`$db->table()->where()`).
- Avoid raw queries. If necessary, use parameter binding:
```php
$db->query("SELECT * FROM users WHERE id = ?", [$id]);
```

### 4. Secure File Uploads
- **Validation**: Use CI4's file validation rules (`uploaded`, `mime_in`, `max_size`).
- **Store Outside Public**: Ideally, store sensitive files outside `public/` and serve them via a controller.
- **Permissions**: Never use `0777` for directories. Use `0755` for directories and `0644` for files.
- **Randomize Names**: Always use `$file->getRandomName()`.

### 5. Secure Session Management
- Set `session.cookieSecure` to `true` if using HTTPS.
- Use `session.httponly = true`.
- Regenerate session ID after login: `session()->regenerate()`.

### 6. Configuration Security
- Never commit `.env` files with production secrets.
- Use `php spark key:generate` to set a secure `app.encryptionKey`.
- Set `CI_ENVIRONMENT` to `production` in production servers to disable detailed error reporting.

## Infrastructure Checklist
- [ ] HTTPS enabled.
- [ ] Content-Security-Policy (CSP) headers configured.
- [ ] HSTS enabled.
- [ ] X-Frame-Options set to `SAMEORIGIN` or `DENY`.
