# SweetAlert Implementation Plan for Kartu yang Dihapus

## Steps to Complete:

1. **Modify detail.blade.php**:
   - Replace anchor tags for "Pulihkan" and "Hapus Permanen" with buttons
   - Add data attributes for activity ID and action type
   - Add JavaScript for SweetAlert confirmation and AJAX calls

2. **Implement AJAX Functions**:
   - Create functions for restore and permanent delete operations
   - Handle success/error responses with SweetAlert
   - Update the UI dynamically without page reload

3. **Test the Implementation**:
   - Verify SweetAlert dialogs appear correctly
   - Test AJAX calls work properly
   - Confirm UI updates without page reload

## Current Status:
- [ ] Modify detail.blade.php
- [ ] Implement AJAX functions
- [ ] Test the implementation

## Files to Modify:
- resources/views/admin/activities/detail.blade.php
