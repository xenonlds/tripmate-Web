# Database Schema Update: Admin Hide Columns

## Overview
This update separates **user privacy controls** from **admin moderation controls** in the Community table.

## Problem
Previously, the `is_hidden` column was used for both:
- User privacy settings (user wants to make post private)
- Admin moderation (admin hides post due to warnings/violations)

When a user received a 2nd warning, the system hid their post using `is_hidden = true`. However, users could simply unhide it using their "set public" function, bypassing the moderation action.

## Solution
Added two new columns to the `Community` table:

### New Columns

| Column | Type | Default | Description |
|--------|------|---------|-------------|
| `hidden_by_admin` | boolean | false | Admin/system enforced hiding. Cannot be overridden by user. |
| `admin_hide_reason` | varchar | null | Reason for admin hiding (e.g., "2nd warning", "User blocked") |

### Column Usage

- **`is_hidden`** - User privacy control (user sets post as private/public)
- **`hidden_by_admin`** - Admin moderation control (cannot be changed by user)

### Visibility Logic

A post is **visible** only when:
```
is_hidden = false AND hidden_by_admin = false
```

A post is **hidden** when:
```
is_hidden = true (user private) OR hidden_by_admin = true (admin moderation)
```

## Implementation Changes

### Backend Changes

1. **AdminCommunityController.php**
   - Warning system now uses `hidden_by_admin` instead of `is_hidden`
   - 2nd warning sets `hidden_by_admin = true` (user cannot unhide)
   - Block user sets `hidden_by_admin = true` on all user posts
   - Status filter checks both columns for proper filtering

2. **MemberDetailsController.php**
   - Visibility statistics count both user-hidden and admin-hidden posts
   - Toggle visibility function uses `hidden_by_admin` for admin controls

### Frontend Changes

1. **CommunityManagement.blade.php**
   - Status badges distinguish between "Hidden by Admin" and "Hidden by User"
   - Visibility buttons control `hidden_by_admin` only
   - Shows "User Private" indicator when user sets post as private

2. **CommunityPostDetail.blade.php**
   - Updated visibility toggle to use admin controls
   - Status badge shows both user privacy and admin moderation state
   - Clear indication when post is private vs. admin-hidden

3. **MemberDetails.blade.php**
   - Post cards show different badges for admin vs user hiding
   - Toggle buttons clearly labeled as "(Admin)" control

## Migration Steps

### 1. Run Database Migration

Execute the SQL migration file on your Supabase database:

```bash
# Connect to your Supabase database and run:
psql -h your-supabase-url -d postgres -f database/migrations/add_admin_hide_columns_to_community.sql
```

Or run directly in Supabase SQL Editor:
1. Go to Supabase Dashboard > SQL Editor
2. Copy contents of `database/migrations/add_admin_hide_columns_to_community.sql`
3. Run the migration

### 2. Verify Migration

Run the verification query in Supabase SQL Editor:

```sql
SELECT 
    postID, 
    title, 
    is_hidden as user_hidden, 
    hidden_by_admin as admin_hidden,
    admin_hide_reason,
    CASE 
        WHEN hidden_by_admin = true THEN 'Hidden by Admin (enforced)'
        WHEN is_hidden = true THEN 'Hidden by User (privacy)'
        ELSE 'Visible'
    END as visibility_status
FROM public.Community
ORDER BY created_at DESC
LIMIT 20;
```

### 3. Deploy Code Changes

The PHP code changes are already implemented. No additional deployment steps needed.

### 4. Test the System

Test the following scenarios:

1. **User Privacy Control**
   - User sets post to private → `is_hidden = true`
   - User sets post to public → `is_hidden = false`
   - Admin can still see these posts in admin panel

2. **Admin Moderation**
   - Issue 2nd warning → Post hidden by admin (`hidden_by_admin = true`)
   - User tries to unhide → Post remains hidden (cannot override)
   - Admin unhides → `hidden_by_admin = false`

3. **User Blocking**
   - Block user → All their posts have `hidden_by_admin = true`
   - Unblock user → `hidden_by_admin` remains true (must be manually unhidden)

## Security Benefits

1. **Moderation Enforcement**: Users cannot bypass admin moderation actions
2. **Clear Audit Trail**: `admin_hide_reason` tracks why content was hidden
3. **Separation of Concerns**: User privacy and admin moderation are independent
4. **Better Compliance**: Supports content moderation policies and terms of service enforcement

## Rollback Plan

If you need to rollback:

```sql
-- Remove the new columns
ALTER TABLE public.Community 
DROP COLUMN IF EXISTS hidden_by_admin,
DROP COLUMN IF EXISTS admin_hide_reason;

-- Revert code changes using git
git revert <commit-hash>
```

## Notes

- Existing posts with `is_hidden = true` are treated as user-hidden (privacy)
- If you want to migrate existing hidden posts to admin-hidden, uncomment the UPDATE statement in the migration file
- The index on `hidden_by_admin` improves query performance for filtering hidden posts

## Support

For issues or questions, please contact the development team.
