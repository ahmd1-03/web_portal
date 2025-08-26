# TODO: Fix Restore Functionality

## Tasks to Complete:

1. [ ] Fix ActivityController restore method validation and logic
2. [ ] Enhance JavaScript error handling in detail.blade.php
3. [ ] Test both activity and card restoration scenarios
4. [ ] Verify UI updates work correctly without page reload

## Current Issues Identified:

1. ActivityController restore method may not properly validate if card is trashed
2. JavaScript error handling could be more specific
3. Need to ensure proper error messages for different scenarios

## Files to Modify:
- app/Http/Controllers/Admin/ActivityController.php
- resources/views/admin/activities/detail.blade.php

## Testing Scenarios:
- Restore activity that is not trashed (should show error)
- Restore activity with associated card (should restore both)
- Restore activity without associated card (should restore only activity)
- Network errors (should show proper error message)
