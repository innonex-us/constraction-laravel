-- MySQL initialization script for Docker
-- This script runs when the MySQL container first starts

-- Set character set and collation
ALTER DATABASE construction_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Grant additional privileges if needed
GRANT ALL PRIVILEGES ON construction_db.* TO 'construction_user'@'%';
FLUSH PRIVILEGES;

-- Optional: Create additional databases for testing
CREATE DATABASE IF NOT EXISTS construction_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL PRIVILEGES ON construction_test.* TO 'construction_user'@'%';

FLUSH PRIVILEGES;
