-- Migration: Add missing timestamp columns for notifications
-- Adds updated_at to notification table
-- Adds created_at and updated_at to notification_recipient table

ALTER TABLE notification 
ADD COLUMN updated_at DATETIME NULL AFTER created_at;

ALTER TABLE notification_recipient 
ADD COLUMN created_at DATETIME NULL,
ADD COLUMN updated_at DATETIME NULL;
