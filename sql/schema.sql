CREATE DATABASE IF NOT EXISTS feedback_dashboard;
USE feedback_dashboard;

CREATE TABLE IF NOT EXISTS feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment_text TEXT NOT NULL,
    sentiment_score FLOAT DEFAULT NULL, 
    sentiment_label VARCHAR(10) DEFAULT NULL,
    date_submitted DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO feedback (rating, comment_text, date_submitted) VALUES
(5, 'The staff were amazing and the food came out fast, really great experience', '2026-06-01 12:30:00'),
(2, 'Waited forever and the order was wrong, pretty disappointing overall', '2026-06-03 18:15:00'),
(4, 'Good service, friendly staff, would come back again', '2026-06-05 13:00:00'),
(1, 'Terrible experience, rude staff and cold food', '2026-06-07 19:45:00'),
(5, 'Loved it! Everything was perfect from start to finish', '2026-06-10 12:00:00'),
(3, 'It was okay, nothing special but nothing bad either', '2026-06-12 17:30:00');