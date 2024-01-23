SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


INSERT INTO faqs (id, survey_id, title, text) VALUES
(1, 2, '質問ああ', 'あ'),
(2, 2, '質問いい', 'あああ');

INSERT INTO options (id, faq_id, title, text, is_last, next_faq_id, dial) VALUES
(1, 1, 'あああ選択肢a', NULL, 0, 2, 0),
(2, 1, 'たたた', NULL, 0, NULL, 1);

INSERT INTO send_emails (id, user_id, email) VALUES
(3, 1, 'test2@example.com');

INSERT INTO surveys (id, user_id, title, note, greeting_text, ending_text) VALUES
(1, 1, 'アンケート１', '説明テキスト', NULL, NULL),
(2, 1, 'アンケ２あ', 'アああ', NULL, NULL);

INSERT INTO users (id, email, password) VALUES
(1, 'test@example.com', '$2y$10$5V09cOUQwo9Z6sbb3TFGZeG2bzepPgnk.U8ZW6p4FdGDe3AcXDtOq');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
