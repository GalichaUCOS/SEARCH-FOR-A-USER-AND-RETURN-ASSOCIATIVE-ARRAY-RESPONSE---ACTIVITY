CREATE TABLE user_accounts (
	user_id INT AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(255),
	first_name VARCHAR(255),
	last_name VARCHAR(255),
	password TEXT,
	date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);

CREATE TABLE applicants (
	applicant_id INT AUTO_INCREMENT PRIMARY KEY,
	address VARCHAR(255),
	applicant VARCHAR(255),
	contact_number VARCHAR(255),
	date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
	added_by VARCHAR(255),
	last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	last_updated_by VARCHAR(255)
);

CREATE TABLE activity_logs (
	activity_log_id INT AUTO_INCREMENT PRIMARY KEY,
	operation VARCHAR(255),
	branch_id INT,
	address VARCHAR(255),
	head_manager VARCHAR(255),
	contact_number VARCHAR (255),
	username VARCHAR(255),
	date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);
