ALTER TABLE users
ADD COLUMN headline VARCHAR(180) DEFAULT NULL AFTER role,
ADD COLUMN bio TEXT DEFAULT NULL AFTER headline,
ADD COLUMN location VARCHAR(120) DEFAULT NULL AFTER bio,
ADD COLUMN website VARCHAR(255) DEFAULT NULL AFTER location,
ADD COLUMN profile_image VARCHAR(255) DEFAULT NULL AFTER website,
ADD COLUMN company_name VARCHAR(180) DEFAULT NULL AFTER profile_image,
ADD COLUMN company_description TEXT DEFAULT NULL AFTER company_name,
ADD COLUMN company_website VARCHAR(255) DEFAULT NULL AFTER company_description,
ADD COLUMN company_location VARCHAR(120) DEFAULT NULL AFTER company_website,
ADD COLUMN company_logo VARCHAR(255) DEFAULT NULL AFTER company_location;

CREATE TABLE saved_jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    job_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_saved_jobs_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_saved_jobs_job FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    UNIQUE KEY uq_saved_jobs_user_job (user_id, job_id)
);
