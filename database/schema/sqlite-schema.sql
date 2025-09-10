CREATE TABLE IF NOT EXISTS "migrations"(
  "id" integer primary key autoincrement not null,
  "migration" varchar not null,
  "batch" integer not null
);
CREATE TABLE IF NOT EXISTS "users"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "email" varchar not null,
  "email_verified_at" datetime,
  "password" varchar not null,
  "remember_token" varchar,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "users_email_unique" on "users"("email");
CREATE TABLE IF NOT EXISTS "password_reset_tokens"(
  "email" varchar not null,
  "token" varchar not null,
  "created_at" datetime,
  primary key("email")
);
CREATE TABLE IF NOT EXISTS "sessions"(
  "id" varchar not null,
  "user_id" integer,
  "ip_address" varchar,
  "user_agent" text,
  "payload" text not null,
  "last_activity" integer not null,
  primary key("id")
);
CREATE INDEX "sessions_user_id_index" on "sessions"("user_id");
CREATE INDEX "sessions_last_activity_index" on "sessions"("last_activity");
CREATE TABLE IF NOT EXISTS "cache"(
  "key" varchar not null,
  "value" text not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "cache_locks"(
  "key" varchar not null,
  "owner" varchar not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "jobs"(
  "id" integer primary key autoincrement not null,
  "queue" varchar not null,
  "payload" text not null,
  "attempts" integer not null,
  "reserved_at" integer,
  "available_at" integer not null,
  "created_at" integer not null
);
CREATE INDEX "jobs_queue_index" on "jobs"("queue");
CREATE TABLE IF NOT EXISTS "job_batches"(
  "id" varchar not null,
  "name" varchar not null,
  "total_jobs" integer not null,
  "pending_jobs" integer not null,
  "failed_jobs" integer not null,
  "failed_job_ids" text not null,
  "options" text,
  "cancelled_at" integer,
  "created_at" integer not null,
  "finished_at" integer,
  primary key("id")
);
CREATE TABLE IF NOT EXISTS "failed_jobs"(
  "id" integer primary key autoincrement not null,
  "uuid" varchar not null,
  "connection" text not null,
  "queue" text not null,
  "payload" text not null,
  "exception" text not null,
  "failed_at" datetime not null default CURRENT_TIMESTAMP
);
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs"("uuid");
CREATE TABLE IF NOT EXISTS "projects"(
  "id" integer primary key autoincrement not null,
  "title" varchar not null,
  "slug" varchar not null,
  "excerpt" text,
  "content" text,
  "location" varchar,
  "client" varchar,
  "status" varchar not null default 'completed',
  "category" varchar,
  "featured_image" varchar,
  "gallery" text,
  "started_at" date,
  "completed_at" date,
  "is_featured" tinyint(1) not null default '0',
  "meta_title" varchar,
  "meta_description" text,
  "created_at" datetime,
  "updated_at" datetime,
  "lat" numeric,
  "lng" numeric
);
CREATE UNIQUE INDEX "projects_slug_unique" on "projects"("slug");
CREATE TABLE IF NOT EXISTS "services"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "slug" varchar not null,
  "excerpt" text,
  "content" text,
  "icon" varchar,
  "image" varchar,
  "order" integer not null default '0',
  "is_active" tinyint(1) not null default '1',
  "meta_title" varchar,
  "meta_description" text,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "services_slug_unique" on "services"("slug");
CREATE TABLE IF NOT EXISTS "site_settings"(
  "id" integer primary key autoincrement not null,
  "site_name" varchar,
  "logo_path" varchar,
  "primary_color" varchar,
  "secondary_color" varchar,
  "address" text,
  "phone" varchar,
  "email" varchar,
  "headline" varchar,
  "hero_video_url" varchar,
  "social_links" text,
  "theme" varchar not null default 'default',
  "created_at" datetime,
  "updated_at" datetime,
  "subheadline" text,
  "stat_years" varchar,
  "stat_projects" varchar,
  "stat_emr" varchar,
  "cta_heading" varchar,
  "cta_text" text,
  "cta_button_text" varchar,
  "cta_button_url" varchar,
  "show_services_section" tinyint(1) not null default '1',
  "show_projects_section" tinyint(1) not null default '1',
  "show_testimonials_section" tinyint(1) not null default '1',
  "show_clients_section" tinyint(1) not null default '1',
  "show_news_section" tinyint(1) not null default '1',
  "show_badges_section" tinyint(1) not null default '1',
  "services_section_heading" varchar not null default 'Services',
  "projects_section_heading" varchar not null default 'Featured Projects',
  "testimonials_section_heading" varchar not null default 'What clients say',
  "clients_section_heading" varchar not null default 'Our Clients',
  "news_section_heading" varchar not null default 'Latest News',
  "badges_section_heading" varchar not null default 'Certifications & Affiliations',
  "services_limit" integer not null default '6',
  "projects_limit" integer not null default '6',
  "testimonials_limit" integer not null default '6',
  "news_limit" integer not null default '3',
  "hero_image" varchar
);
CREATE TABLE IF NOT EXISTS "team_members"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "role" varchar,
  "bio" text,
  "photo" varchar,
  "linkedin_url" varchar,
  "order" integer not null default '0',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "testimonials"(
  "id" integer primary key autoincrement not null,
  "author_name" varchar not null,
  "author_title" varchar,
  "company" varchar,
  "content" text not null,
  "rating" integer,
  "avatar_image" varchar,
  "is_featured" tinyint(1) not null default '0',
  "order" integer not null default '0',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "careers"(
  "id" integer primary key autoincrement not null,
  "title" varchar not null,
  "location" varchar,
  "department" varchar,
  "description" text not null,
  "apply_url" varchar,
  "is_open" tinyint(1) not null default '1',
  "posted_at" date,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "contact_messages"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "email" varchar not null,
  "phone" varchar,
  "subject" varchar,
  "message" text not null,
  "status" varchar not null default 'new',
  "resolved_at" datetime,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "pages"(
  "id" integer primary key autoincrement not null,
  "title" varchar not null,
  "slug" varchar not null,
  "content" text,
  "hero_image" varchar,
  "meta_title" varchar,
  "meta_description" text,
  "is_published" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime,
  "show_in_nav" tinyint(1) not null default '0',
  "nav_order" integer not null default '0'
);
CREATE UNIQUE INDEX "pages_slug_unique" on "pages"("slug");
CREATE TABLE IF NOT EXISTS "posts"(
  "id" integer primary key autoincrement not null,
  "title" varchar not null,
  "slug" varchar not null,
  "excerpt" text,
  "body" text not null,
  "featured_image" varchar,
  "published_at" datetime,
  "is_published" tinyint(1) not null default '0',
  "meta_title" varchar,
  "meta_description" text,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "posts_slug_unique" on "posts"("slug");
CREATE TABLE IF NOT EXISTS "gallery_items"(
  "id" integer primary key autoincrement not null,
  "title" varchar not null,
  "slug" varchar not null,
  "image" varchar not null,
  "category" varchar,
  "caption" text,
  "order" integer not null default '0',
  "is_published" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "gallery_items_slug_unique" on "gallery_items"("slug");
CREATE TABLE IF NOT EXISTS "badges"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "slug" varchar not null,
  "image" varchar,
  "url" varchar,
  "order" integer not null default '0',
  "is_active" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "badges_slug_unique" on "badges"("slug");
CREATE TABLE IF NOT EXISTS "safety_records"(
  "id" integer primary key autoincrement not null,
  "year" integer not null,
  "emr" numeric,
  "trir" numeric,
  "ltir" numeric,
  "total_hours" integer,
  "osha_recordables" integer,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "prequalifications"(
  "id" integer primary key autoincrement not null,
  "company_name" varchar not null,
  "contact_name" varchar,
  "email" varchar,
  "phone" varchar,
  "trade" varchar,
  "license_number" varchar,
  "years_in_business" integer,
  "annual_revenue" integer,
  "bonding_capacity" integer,
  "emr" numeric,
  "trir" numeric,
  "safety_contact" varchar,
  "insurance_carrier" varchar,
  "coverage" varchar,
  "address" varchar,
  "city" varchar,
  "state" varchar,
  "zip" varchar,
  "website" varchar,
  "notes" text,
  "documents" text,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "pages_show_in_nav_nav_order_index" on "pages"(
  "show_in_nav",
  "nav_order"
);
CREATE TABLE IF NOT EXISTS "clients"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "slug" varchar not null,
  "logo" varchar,
  "website_url" varchar,
  "order" integer not null default '0',
  "is_active" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "clients_slug_unique" on "clients"("slug");

INSERT INTO migrations VALUES(1,'0001_01_01_000000_create_users_table',1);
INSERT INTO migrations VALUES(2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO migrations VALUES(3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO migrations VALUES(4,'2025_09_06_075410_create_projects_table',2);
INSERT INTO migrations VALUES(5,'2025_09_06_075410_create_services_table',2);
INSERT INTO migrations VALUES(6,'2025_09_06_075410_create_site_settings_table',2);
INSERT INTO migrations VALUES(7,'2025_09_06_075410_create_team_members_table',2);
INSERT INTO migrations VALUES(8,'2025_09_06_075410_create_testimonials_table',2);
INSERT INTO migrations VALUES(9,'2025_09_06_075411_create_careers_table',2);
INSERT INTO migrations VALUES(10,'2025_09_06_075411_create_contact_messages_table',2);
INSERT INTO migrations VALUES(11,'2025_09_06_075411_create_pages_table',2);
INSERT INTO migrations VALUES(12,'2025_09_06_075411_create_posts_table',2);
INSERT INTO migrations VALUES(13,'2025_09_06_082623_create_gallery_items_table',3);
INSERT INTO migrations VALUES(14,'2025_09_06_083712_create_badges_table',4);
INSERT INTO migrations VALUES(15,'2025_09_06_085246_create_safety_records_table',5);
INSERT INTO migrations VALUES(16,'2025_09_06_085247_add_lat_lng_to_projects_table',5);
INSERT INTO migrations VALUES(17,'2025_09_06_085247_create_prequalifications_table',5);
INSERT INTO migrations VALUES(18,'2025_09_07_000001_add_nav_fields_to_pages_table',6);
INSERT INTO migrations VALUES(19,'2025_09_07_000002_add_home_page_fields_to_site_settings_table',6);
INSERT INTO migrations VALUES(20,'2025_09_10_064223_create_clients_table',7);
INSERT INTO migrations VALUES(21,'2025_09_10_064443_add_section_controls_to_site_settings_table',8);
INSERT INTO migrations VALUES(22,'2025_09_10_065207_add_hero_image_to_site_settings_table',9);
INSERT INTO migrations VALUES(23,'2025_09_10_065218_add_hero_image_to_site_settings_table',9);
