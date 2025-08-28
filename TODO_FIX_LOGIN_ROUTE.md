# TODO: Fix Login Route and Storage Permissions

## Issues to Fix:
1. [ ] Fix "Route [login] not defined" error in Authenticate middleware
2. [ ] Fix "Route [login] not defined" error in welcome.blade.php
3. [ ] Fix storage directory permissions (403 Forbidden errors)

## Steps:
1. [ ] Edit app/Http/Middleware/Authenticate.php - change route('login') to route('admin.login')
2. [ ] Edit resources/views/welcome.blade.php - change route('login') to route('admin.login')
3. [ ] Execute chmod commands to fix storage permissions
4. [ ] Test the fixes

## Files to Edit:
- app/Http/Middleware/Authenticate.php
- resources/views/welcome.blade.php

## Commands to Run:
- chmod -R 775 storage/
- chmod -R 775 bootstrap/cache/
