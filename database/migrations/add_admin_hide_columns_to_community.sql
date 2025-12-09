-- Migration: Add admin hide columns to Community table
-- Purpose: Separate user privacy controls from admin moderation controls
-- Date: December 9, 2025
--
-- This migration adds two new columns to the Community table:
-- 1. hidden_by_admin: Boolean flag to indicate if post is hidden by admin/system (cannot be overridden by user)
-- 2. admin_hide_reason: Text field to store the reason why admin hid the post
--
-- IMPORTANT: Run this migration on your Supabase database

-- Add hidden_by_admin column (admin/system enforced hiding)
ALTER TABLE public.Community
ADD COLUMN IF NOT EXISTS hidden_by_admin boolean DEFAULT false;

-- Add admin_hide_reason column (track reason for admin hiding)
ALTER TABLE public.Community
ADD COLUMN IF NOT EXISTS admin_hide_reason character varying;

-- Add comment to document the column purpose
COMMENT ON COLUMN public.Community.hidden_by_admin IS 'Admin/system enforced hiding. Cannot be overridden by user privacy settings. Used for content moderation.';
COMMENT ON COLUMN public.Community.admin_hide_reason IS 'Reason why the post was hidden by admin or system (e.g., "2nd warning", "User blocked", "Manual moderation")';

-- Create index for better query performance when filtering by admin hidden status
CREATE INDEX IF NOT EXISTS idx_community_hidden_by_admin ON public.Community(hidden_by_admin) WHERE hidden_by_admin = true;

-- Update existing data (optional - only if you want to migrate existing hidden posts)
-- This will convert all currently hidden posts to be admin-hidden
-- Comment out if you want existing hidden posts to remain user-hidden
-- UPDATE public.Community
-- SET hidden_by_admin = true,
--     admin_hide_reason = 'Migrated from old is_hidden column'
-- WHERE is_hidden = true;

-- Verification query - Run this to verify the migration
-- SELECT
--     postID,
--     title,
--     is_hidden as user_hidden,
--     hidden_by_admin as admin_hidden,
--     admin_hide_reason,
--     CASE
--         WHEN hidden_by_admin = true THEN 'Hidden by Admin (enforced)'
--         WHEN is_hidden = true THEN 'Hidden by User (privacy)'
--         ELSE 'Visible'
--     END as visibility_status
-- FROM public.Community
-- ORDER BY created_at DESC
-- LIMIT 20;
