-- Disease Management Database Schema
-- Created by [Your Name] on [Date]

CREATE DATABASE IF NOT EXISTS disease_management;
USE disease_management;

-- Patient Table
CREATE TABLE IF NOT EXISTS patient (
    id INT AUTO_INCREMENT PRIMARY KEY,
    Last_name VARCHAR(512) NOT NULL,
    First_name VARCHAR(512) NOT NULL,
    Gender VARCHAR(512),
    birthdate DATE,
    address VARCHAR(512),
    contact_phone VARCHAR(512),
    email VARCHAR(512),
    registration_day DATE,
    doctor_id INT,
    chronic_diseases VARCHAR(512),
    medical_history TEXT,
    height_cm VARCHAR(512),
    weight_kg VARCHAR(512),
    bmi VARCHAR(512),
    smoking_alcohol VARCHAR(512),
    surgical_history TEXT,
    physical_condition VARCHAR(512),
    blood_type VARCHAR(512),
    drug_allergies TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (doctor_id) REFERENCES doctor(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Doctor Table
CREATE TABLE doctor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(512) NOT NULL UNIQUE,
    password VARCHAR(512) NOT NULL,
    full_name VARCHAR(512) NOT NULL,
    CIN VARCHAR(512) NOT NULL UNIQUE,
    email VARCHAR(512)  UNIQUE,
    contact_info VARCHAR(512),
    specialization VARCHAR(512) NOT NULL,
    hospital_affiliation VARCHAR(512),
    status VARCHAR(512),
    role ENUM('admin', 'non admin') NOT NULL,
    professional_memberships1 TEXT,
    professional_memberships2 TEXT,
    working_hours INT,
    Degree VARCHAR(40),
    years_of_experience INT,
    medical_license_number VARCHAR(40) NOT NULL UNIQUE,
    license_issuing_authority VARCHAR(512),
    license_issue_date DATE,
    license_expiry_date DATE,
    official_working_hours TEXT,
    weekly_days_off VARCHAR(50),
    gender ENUM('Female', 'Male', 'Prefer not to say'),
    doctor_removed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Diagnosis Table
CREATE TABLE diagnosis (
    diagnosis_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    diagnosis_name VARCHAR(512) NOT NULL,
    diagnosis_date DATE NOT NULL,
    diagnosis_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patient(patient_id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctor(doctor_id) ON DELETE CASCADE
);

-- Symptoms Table
CREATE TABLE IF NOT EXISTS symptoms (
    symptom_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    symptom_name VARCHAR(512) NOT NULL,
    severity VARCHAR(512),
    start_date DATE NOT NULL,
    duration_days INT,
    registration_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patient(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Treatments Table
CREATE TABLE IF NOT EXISTS treatments (
    treatment_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    diagnosis_id INT NOT NULL,
    treatment_name VARCHAR(512) NOT NULL,
    medication_name VARCHAR(512),
    dosage VARCHAR(512),
    treatment_notes TEXT,
    treatment_duration INT COMMENT 'Duration in days',
    start_date DATE DEFAULT CURRENT_DATE,
    end_date DATE,
    status ENUM('Planned', 'Ongoing', 'Completed', 'Cancelled') DEFAULT 'Planned',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patient(id) ON DELETE CASCADE,
    FOREIGN KEY (diagnosis_id) REFERENCES diagnosis(diagnosis_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Support Requests Table
CREATE TABLE IF NOT EXISTS support_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    priority ENUM('Low', 'Normal', 'Urgent') DEFAULT 'Normal',
    status ENUM('Pending', 'Resolved') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Notifications Table
CREATE TABLE notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT,
    title VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    notification_type ENUM('Appointment', 'Medication', 'General', 'Alert') NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patient(patient_id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctor(doctor_id) ON DELETE SET NULL
);

-- Access Dates Table (for tracking patient access)
CREATE TABLE access_dates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    access_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
);

-- Create indexes for better performance
CREATE INDEX idx_diagnosis_patient ON diagnosis(patient_id);
CREATE INDEX idx_diagnosis_doctor ON diagnosis(doctor_id);
CREATE INDEX idx_symptoms_diagnosis ON symptoms(diagnosis_id);
CREATE INDEX idx_treatments_diagnosis ON treatments(diagnosis_id);
CREATE INDEX idx_notifications_patient ON notifications(patient_id);
CREATE INDEX idx_access_dates_patient ON access_dates(patient_id);