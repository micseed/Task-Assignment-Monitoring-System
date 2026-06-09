-- ============================================================
--  Task Assignment & Monitoring System
--  Database Schema v1.0
--  Generated: 2026-06-07
-- ============================================================

-- ============================================================
--  1. USERS
--     Stores all system users: students, teachers, and admins.
-- ============================================================
CREATE TABLE users (
    user_id         INT             NOT NULL AUTO_INCREMENT,
    first_name      VARCHAR(100)    NOT NULL,
    last_name       VARCHAR(100)    NOT NULL,
    email           VARCHAR(255)    NOT NULL,
    password_hash   VARCHAR(255)    NOT NULL,
    role            ENUM('student','teacher','admin') NOT NULL,
    department_id   INT             NULL,           -- FK → departments (set after departments exist)
    profile_picture VARCHAR(500)    NULL,
    is_active       BOOLEAN         NOT NULL DEFAULT TRUE,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (user_id),
    UNIQUE KEY uq_users_email (email)
);

-- ============================================================
--  2. DEPARTMENTS
--     Academic departments managed by the admin/dean.
-- ============================================================
CREATE TABLE departments (
    department_id   INT             NOT NULL AUTO_INCREMENT,
    name            VARCHAR(150)    NOT NULL,
    code            VARCHAR(20)     NOT NULL,
    dean_id         INT             NULL,           -- FK → users (admin/dean)
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (department_id),
    UNIQUE KEY uq_departments_name (name),
    UNIQUE KEY uq_departments_code (code),

    CONSTRAINT fk_departments_dean
        FOREIGN KEY (dean_id) REFERENCES users (user_id)
        ON DELETE SET NULL ON UPDATE CASCADE
);

-- Add the FK from users → departments now that departments exists
ALTER TABLE users
    ADD CONSTRAINT fk_users_department
        FOREIGN KEY (department_id) REFERENCES departments (department_id)
        ON DELETE SET NULL ON UPDATE CASCADE;

-- ============================================================
--  3. SUBJECTS
--     Courses offered, linked to a department and a teacher.
-- ============================================================
CREATE TABLE subjects (
    subject_id      INT             NOT NULL AUTO_INCREMENT,
    subject_code    VARCHAR(30)     NOT NULL,
    subject_name    VARCHAR(200)    NOT NULL,
    department_id   INT             NOT NULL,
    teacher_id      INT             NULL,           -- NULL if not yet assigned
    school_year     VARCHAR(20)     NOT NULL,       -- e.g. '2024-2025'
    semester        ENUM('1st','2nd','Summer') NOT NULL,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (subject_id),
    UNIQUE KEY uq_subjects_code (subject_code),

    CONSTRAINT fk_subjects_department
        FOREIGN KEY (department_id) REFERENCES departments (department_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,

    CONSTRAINT fk_subjects_teacher
        FOREIGN KEY (teacher_id) REFERENCES users (user_id)
        ON DELETE SET NULL ON UPDATE CASCADE
);

-- ============================================================
--  4. CLASSES
--     A specific section of students enrolled in a subject.
-- ============================================================
CREATE TABLE classes (
    class_id        INT             NOT NULL AUTO_INCREMENT,
    subject_id      INT             NOT NULL,
    section         VARCHAR(50)     NOT NULL,       -- e.g. 'BSIT 3-A'
    school_year     VARCHAR(20)     NOT NULL,
    semester        ENUM('1st','2nd','Summer') NOT NULL,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (class_id),

    CONSTRAINT fk_classes_subject
        FOREIGN KEY (subject_id) REFERENCES subjects (subject_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- ============================================================
--  5. CLASS ENROLLMENTS
--     Junction table — links students to their classes.
-- ============================================================
CREATE TABLE class_enrollments (
    enrollment_id   INT             NOT NULL AUTO_INCREMENT,
    class_id        INT             NOT NULL,
    student_id      INT             NOT NULL,
    enrolled_at     TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status          ENUM('active','dropped','completed') NOT NULL DEFAULT 'active',

    PRIMARY KEY (enrollment_id),
    UNIQUE KEY uq_enrollment (class_id, student_id),

    CONSTRAINT fk_enrollment_class
        FOREIGN KEY (class_id) REFERENCES classes (class_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,

    CONSTRAINT fk_enrollment_student
        FOREIGN KEY (student_id) REFERENCES users (user_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- ============================================================
--  6. ASSIGNMENTS
--     Tasks created by teachers for a specific class.
-- ============================================================
CREATE TABLE assignments (
    assignment_id   INT             NOT NULL AUTO_INCREMENT,
    class_id        INT             NOT NULL,
    teacher_id      INT             NOT NULL,
    title           VARCHAR(255)    NOT NULL,
    description     TEXT            NULL,
    type            ENUM('pdf_upload','code_submission','both') NOT NULL,
    max_points      DECIMAL(6,2)    NOT NULL,
    due_date        DATETIME        NOT NULL,
    allow_late      BOOLEAN         NOT NULL DEFAULT FALSE,
    is_published    BOOLEAN         NOT NULL DEFAULT FALSE,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (assignment_id),

    CONSTRAINT fk_assignments_class
        FOREIGN KEY (class_id) REFERENCES classes (class_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,

    CONSTRAINT fk_assignments_teacher
        FOREIGN KEY (teacher_id) REFERENCES users (user_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- ============================================================
--  7. SUBMISSIONS
--     Each student's current submission for an assignment.
--     Resubmission updates this row; history is in
--     submission_history.
-- ============================================================
CREATE TABLE submissions (
    submission_id   INT             NOT NULL AUTO_INCREMENT,
    assignment_id   INT             NOT NULL,
    student_id      INT             NOT NULL,
    file_url        VARCHAR(500)    NULL,           -- uploaded PDF path/URL
    code_content    LONGTEXT        NULL,           -- raw code text
    code_language   VARCHAR(50)     NULL,           -- e.g. 'Python', 'Java'
    status          ENUM('submitted','unsubmitted','graded') NOT NULL DEFAULT 'submitted',
    submitted_at    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    is_late         BOOLEAN         NOT NULL DEFAULT FALSE,
    points_earned   DECIMAL(6,2)    NULL,
    feedback        TEXT            NULL,
    graded_at       DATETIME        NULL,
    graded_by       INT             NULL,           -- FK → users (teacher)

    PRIMARY KEY (submission_id),
    UNIQUE KEY uq_submission (assignment_id, student_id),

    CONSTRAINT fk_submissions_assignment
        FOREIGN KEY (assignment_id) REFERENCES assignments (assignment_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,

    CONSTRAINT fk_submissions_student
        FOREIGN KEY (student_id) REFERENCES users (user_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,

    CONSTRAINT fk_submissions_grader
        FOREIGN KEY (graded_by) REFERENCES users (user_id)
        ON DELETE SET NULL ON UPDATE CASCADE
);

-- ============================================================
--  8. SUBMISSION HISTORY
--     Audit trail — one row per submit / unsubmit / resubmit
--     event, preserving each file/code version.
-- ============================================================
CREATE TABLE submission_history (
    history_id      INT             NOT NULL AUTO_INCREMENT,
    submission_id   INT             NOT NULL,
    file_url        VARCHAR(500)    NULL,
    code_content    LONGTEXT        NULL,
    action          ENUM('submitted','unsubmitted','resubmitted') NOT NULL,
    action_at       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (history_id),

    CONSTRAINT fk_sub_history_submission
        FOREIGN KEY (submission_id) REFERENCES submissions (submission_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- ============================================================
--  9. NOTIFICATIONS
--     In-app alerts: reminders, grade releases, deadlines.
-- ============================================================
CREATE TABLE notifications (
    notification_id INT             NOT NULL AUTO_INCREMENT,
    recipient_id    INT             NOT NULL,
    sender_id       INT             NULL,           -- NULL = system-generated
    type            ENUM('reminder','grade_released','deadline_warning','system') NOT NULL,
    title           VARCHAR(255)    NOT NULL,
    message         TEXT            NOT NULL,
    is_read         BOOLEAN         NOT NULL DEFAULT FALSE,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (notification_id),

    CONSTRAINT fk_notifications_recipient
        FOREIGN KEY (recipient_id) REFERENCES users (user_id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_notifications_sender
        FOREIGN KEY (sender_id) REFERENCES users (user_id)
        ON DELETE SET NULL ON UPDATE CASCADE
);

-- ============================================================
--  10. REMINDERS
--      Teacher-initiated bulk messages to non-submitting
--      students for a specific assignment.
-- ============================================================
CREATE TABLE reminders (
    reminder_id     INT             NOT NULL AUTO_INCREMENT,
    assignment_id   INT             NOT NULL,
    sent_by         INT             NOT NULL,       -- teacher
    message         TEXT            NOT NULL,
    sent_at         TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    target          ENUM('non_submitters','all','specific') NOT NULL DEFAULT 'non_submitters',

    PRIMARY KEY (reminder_id),

    CONSTRAINT fk_reminders_assignment
        FOREIGN KEY (assignment_id) REFERENCES assignments (assignment_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,

    CONSTRAINT fk_reminders_teacher
        FOREIGN KEY (sent_by) REFERENCES users (user_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- ============================================================
--  11. CALENDAR INTEGRATIONS
--      Google Calendar sync tokens per student per assignment.
-- ============================================================
CREATE TABLE calendar_integrations (
    calendar_id     INT             NOT NULL AUTO_INCREMENT,
    student_id      INT             NOT NULL,
    assignment_id   INT             NOT NULL,
    google_event_id VARCHAR(255)    NOT NULL,       -- used to update/delete the GCal event
    synced_at       TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (calendar_id),
    UNIQUE KEY uq_calendar (student_id, assignment_id),

    CONSTRAINT fk_calendar_student
        FOREIGN KEY (student_id) REFERENCES users (user_id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_calendar_assignment
        FOREIGN KEY (assignment_id) REFERENCES assignments (assignment_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- ============================================================
--  INDEXES  (performance on common queries)
-- ============================================================

-- Users
CREATE INDEX idx_users_role           ON users (role);
CREATE INDEX idx_users_department     ON users (department_id);

-- Assignments
CREATE INDEX idx_assignments_class    ON assignments (class_id);
CREATE INDEX idx_assignments_due      ON assignments (due_date);
CREATE INDEX idx_assignments_published ON assignments (is_published);

-- Submissions
CREATE INDEX idx_submissions_status   ON submissions (status);
CREATE INDEX idx_submissions_student  ON submissions (student_id);

-- Notifications
CREATE INDEX idx_notifications_recipient ON notifications (recipient_id);
CREATE INDEX idx_notifications_read      ON notifications (is_read);

-- Submission history
CREATE INDEX idx_sub_history_submission ON submission_history (submission_id);

-- ============================================================
--  SAMPLE SEED DATA
-- ============================================================

-- Admin / Dean
INSERT INTO users (first_name, last_name, email, password_hash, role) VALUES
('Maria', 'Santos',  'admin@school.edu',   '$2b$12$examplehash001', 'admin');

-- Departments
INSERT INTO departments (name, code, dean_id) VALUES
('Information Technology',       'IT',  1),
('Computer Science',             'CS',  1),
('Information Systems',          'IS',  1);

-- Teachers
INSERT INTO users (first_name, last_name, email, password_hash, role, department_id) VALUES
('Jose',   'Reyes',   'jose.reyes@school.edu',   '$2b$12$examplehash002', 'teacher', 1),
('Ana',    'Cruz',    'ana.cruz@school.edu',      '$2b$12$examplehash003', 'teacher', 2);

-- Students
INSERT INTO users (first_name, last_name, email, password_hash, role, department_id) VALUES
('Juan',   'Dela Cruz', 'juan@student.edu',  '$2b$12$examplehash004', 'student', 1),
('Lea',    'Gomez',     'lea@student.edu',   '$2b$12$examplehash005', 'student', 1),
('Marco',  'Bautista',  'marco@student.edu', '$2b$12$examplehash006', 'student', 2);

-- Subjects
INSERT INTO subjects (subject_code, subject_name, department_id, teacher_id, school_year, semester) VALUES
('IT301', 'Web Development',          1, 2, '2024-2025', '2nd'),
('CS201', 'Data Structures',          2, 3, '2024-2025', '2nd');

-- Classes
INSERT INTO classes (subject_id, section, school_year, semester) VALUES
(1, 'BSIT 3-A', '2024-2025', '2nd'),
(2, 'BSCS 2-B', '2024-2025', '2nd');

-- Enrollments
INSERT INTO class_enrollments (class_id, student_id) VALUES
(1, 4),  -- Juan → BSIT 3-A
(1, 5),  -- Lea  → BSIT 3-A
(2, 6);  -- Marco → BSCS 2-B

-- Assignments
INSERT INTO assignments (class_id, teacher_id, title, description, type, max_points, due_date, is_published) VALUES
(1, 2, 'HTML & CSS Portfolio',
 'Build a personal portfolio site using HTML5 and CSS3. Submit as a ZIP file converted to PDF.',
 'pdf_upload', 100.00, '2025-03-15 23:59:00', TRUE),

(1, 2, 'JavaScript Calculator',
 'Implement a functional calculator using vanilla JavaScript. Submit your .js source file.',
 'code_submission', 50.00, '2025-03-22 23:59:00', TRUE),

(2, 3, 'Binary Search Tree Implementation',
 'Implement insert, delete, and traversal methods for a BST in Java.',
 'code_submission', 100.00, '2025-03-20 23:59:00', TRUE);

-- ============================================================
--  END OF SCHEMA
-- ============================================================
