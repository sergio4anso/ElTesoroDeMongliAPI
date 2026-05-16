# Local setup - ElTesoroDeMongliAPI

## 1. Copy local config

Copy:

```text
config.example.php -> config.local.php
```

`config.local.php` is ignored by Git and is the only place where local credentials should be stored.

## 2. Database

Create the database:

```sql
CREATE DATABASE eltesorodemongli
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

Create a local database user and grant permissions for development.

Then run:

```text
migrations/001_create_initial_schema.sql
```

## 3. Mail in local development

For local development, keep mail disabled in `config.local.php` unless SMTP is configured.

When mail is disabled, register still creates the user and activation token, but no email is sent. You can activate local test users manually in the database:

```sql
UPDATE users SET active = 1 WHERE mail = 'test@example.com';
```

If SMTP is needed, configure it only in `config.local.php`. Do not commit real SMTP credentials.

## 4. Test endpoints

Register:

```powershell
Invoke-RestMethod `
  -Method Post `
  -Uri "http://localhost/ElTesoroDeMongliAPI/register/" `
  -ContentType "application/json" `
  -Body '{"mail":"test@example.com","password":"Test1234!","nickname":"Tester"}'
```

Login:

```powershell
Invoke-RestMethod `
  -Method Post `
  -Uri "http://localhost/ElTesoroDeMongliAPI/login/" `
  -ContentType "application/json" `
  -Body '{"mail":"test@example.com","password":"Test1234!"}'
```

Get users:

```powershell
Invoke-RestMethod `
  -Method Post `
  -Uri "http://localhost/ElTesoroDeMongliAPI/get_users/" `
  -ContentType "application/json" `
  -Body '{}'
```
