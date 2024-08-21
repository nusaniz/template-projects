CREATE TABLE `tb_role`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active'
);
CREATE TABLE `tb_file_verification`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `uuid` VARCHAR(255) NULL,
    `name` VARCHAR(255) NOT NULL,
    `file` VARCHAR(255) NOT NULL,
    `file_hash` VARCHAR(255) NOT NULL,
    `status` ENUM('valid', 'invalid') NOT NULL,
    `created_at` TIMESTAMP NOT NULL
);
CREATE TABLE `tb_jabatan`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active'
);
CREATE TABLE `tb_users`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `uuid` VARCHAR(255) NULL,
    `username` VARCHAR(255) NOT NULL,
    `full name` VARCHAR(255) NOT NULL,
    `role_id` VARCHAR(255) NOT NULL,
    `divisi_id` BIGINT NULL,
    `jabatan_id` VARCHAR(255) NULL,
    `status` VARCHAR(255) NOT NULL
);
CREATE TABLE `tb_divisi`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NULL,
    `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active'
);
CREATE TABLE `tb_file`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `uuid` VARCHAR(255) NULL,
    `user_id` INT NOT NULL,
    `file_name` VARCHAR(255) NOT NULL,
    `file` VARCHAR(255) NOT NULL,
    `file_hash` VARCHAR(255) NOT NULL,
    `divisi` VARCHAR(255) NULL,
    `jabatan` BIGINT NULL,
    `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    `created_at` TIMESTAMP NOT NULL
);