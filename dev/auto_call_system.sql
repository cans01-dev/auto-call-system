/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

INSERT INTO surveys (user_id, title, note, greeting_text, ending_text) VALUES
(1, 'アンケート１', '説明テキスト', NULL, NULL),
(1, 'アンケ２あ', 'アああ', NULL, NULL);

INSERT INTO faqs (survey_id, title, text) VALUES
(2, '質問ああ', 'あ'),
(2, '質問いい', 'あああ');

INSERT INTO options (faq_id, title, is_last, next_faq_id, dial) VALUES
(1, 'あああ選択肢a', 0, 2, 0),
(1, 'たたた', 0, NULL, 1);

INSERT INTO send_emails (user_id, email) VALUES
(1, 'test2@example.com');

INSERT INTO users (email, password) VALUES
('test@example.com', '$2y$10$5V09cOUQwo9Z6sbb3TFGZeG2bzepPgnk.U8ZW6p4FdGDe3AcXDtOq');
-- COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
