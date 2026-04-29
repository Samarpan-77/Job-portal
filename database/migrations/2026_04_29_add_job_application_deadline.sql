ALTER TABLE jobs
ADD COLUMN application_deadline DATE DEFAULT NULL AFTER image_path;
