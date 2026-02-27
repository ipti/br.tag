-- =============================================
-- Notificações - TAG v3.11.0
-- =============================================

CREATE TABLE IF NOT EXISTS notification (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    title           VARCHAR(255) NOT NULL,
    body            TEXT NOT NULL,
    type            ENUM('info','warning','error','success') DEFAULT 'info',
    source          ENUM('admin','system') DEFAULT 'admin',
    source_event    VARCHAR(100) NULL,
    source_url      VARCHAR(500) NULL,
    created_by      INT NULL,
    school_fk       INT NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    expires_at      DATETIME NULL,
    KEY idx_created (created_at),
    KEY idx_school  (school_fk),
    KEY idx_expires (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS notification_recipient (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    notification_fk INT NOT NULL,
    user_fk         INT NOT NULL,
    is_read         TINYINT(1) DEFAULT 0,
    read_at         DATETIME NULL,
    KEY idx_user_unread (user_fk, is_read),
    CONSTRAINT fk_nr_notification FOREIGN KEY (notification_fk)
        REFERENCES notification(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Registrar no RBAC (auth_item) — type 2 = operation
INSERT IGNORE INTO auth_item (name, type, description) VALUES ('FEAT_NOTIFICATIONS_MANAGE', 2, 'Gerenciar notificações');
INSERT IGNORE INTO auth_item (name, type, description) VALUES ('FEAT_NOTIFICATIONS_INBOX', 2, 'Inbox de notificações');

-- Ativar feature flags
INSERT INTO feature_flags (feature_name, active) VALUES ('FEAT_NOTIFICATIONS_MANAGE', 1)
    ON DUPLICATE KEY UPDATE active = 1;
INSERT INTO feature_flags (feature_name, active) VALUES ('FEAT_NOTIFICATIONS_INBOX', 1)
    ON DUPLICATE KEY UPDATE active = 1;

-- Migration: Add missing timestamp columns for notifications
-- Adds updated_at to notification table
-- Adds created_at and updated_at to notification_recipient table

ALTER TABLE notification 
ADD COLUMN updated_at DATETIME NULL AFTER created_at;

ALTER TABLE notification_recipient 
ADD COLUMN created_at DATETIME NULL,
ADD COLUMN updated_at DATETIME NULL;
