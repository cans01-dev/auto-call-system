USE auto_call_system;
SET FOREIGN_KEY_CHECKS = 0;

INSERT INTO `endings` (`id`, `survey_id`, `title`, `text`, `voice_file`) VALUES
(1, 2, '通常終了', '質問は以上となります。\r\nアンケートにご協力を頂きまして、誠にありがとうございました。どうぞお電話をお切りください。', NULL),
(2, 2, '日程調整', 'それでは、後ほどオペレーターから日程調整のお電話が入りますので、ご対応をよろしくお願いします。\r\n最後までお付き合いいただき、誠にありがとうございました。\r\nどうぞお電話をお切りください。', NULL),
(3, 1, 'vdsav', 'davdav', NULL);

INSERT INTO `faqs` (`id`, `survey_id`, `title`, `text`, `order_num`, `voice_file`) VALUES
(1, 2, '太陽光パネルと蓄電池の導入状況', '次に、太陽光パネルと蓄電池の導入状況についてお尋ねします\r\n太陽光パネルと蓄電池の両方を導入されている方は１を・・・・\r\n太陽光パネルのみを導入されている方は２を・・・・\r\n蓄電池のみ導入されている方は３を・・・・\r\nどちらも導入されていない方は４を・・・・\r\nもう一度お聞きになりたい方は０を押してください。', 4, NULL),
(4, 2, '無料シミュレーション', '以上の結果を踏まえて、現状のご使用状況であれば十分に電気代の削減が可能なご家庭となっております。\r\nつきましては、現状よりどのくらい電気料金が安くなるか、地域の専属アドバイザーが無料シミュレーションを\r\nご自宅にて実施いたしております。\r\n無料シミュレーションをご希望の方は１を・・・・\r\nそれ以外の方は２を・・・・\r\nもう一度お聞きになりたい方は０を押してください。', 6, NULL),
(5, 2, 'お電話口に出て頂いている方', 'ご主人様でしたら１を・・・・\r\n奥様でしたら２を・・・・\r\nその他のご家族の方でしたら３を・・・・\r\nもう一度お聞きになりたい方は０を押してください。\r\n', 5, NULL),
(6, 2, 'お使いの給湯器', '電力プランを把握のために、お使いの給湯器をお尋ねします\r\nガス給湯器、または、灯油ボイラーをお使いの方は１を・・・・\r\nエコキュート、または、電気温水器をお使いの方はは２を・・・・\r\nそのほかの給湯器をお使いの方は３を・・・・\r\nもう一度お聞きになりたい方は０を押してください。', 3, NULL),
(7, 2, 'ご年齢', 'ご年齢についてお尋ねします。\r\n７６歳以上の方は１を・・・・\r\n７０歳から７５歳の方は２を・・・・\r\n３０歳から６９歳の方は３を・・・・\r\n２９歳以下の方は４を・・・・\r\nもう一度お聞きになりたい方は０を押してください。', 1, NULL),
(8, 2, '光熱費', '今回のおすすめプランの条件に合った家庭かどうかの光熱費をお尋ねします\r\n電気料金が毎月１万円以上お使いの方は１を・・・・\r\n１万円以下の方は２を・・・・\r\nもう一度お聞きになりたい方は０を押してください。', 2, NULL),
(9, 2, '現在のお住まい', 'それでは、現在のお住まいについてお尋ねします。\r\n一戸建て持ち家の方は１を・・・・\r\nアパート・マンション、賃貸住宅の方は２を・・・・\r\nもう一度お聞きになりたい方は０を押してください。', 0, NULL),
(18, 1, 'dvvasdsav', NULL, 0, NULL);

INSERT INTO `options` (`id`, `faq_id`, `title`, `dial`, `next_ending_id`, `next_faq_id`) VALUES
(6, 1, '聞き直し', 0, NULL, 1),
(8, 4, '聞き直し', 0, NULL, 4),
(9, 4, '無料シミュレーション希望', 1, 2, NULL),
(10, 4, 'それ以外', 2, 1, NULL),
(11, 5, '聞き直し', 0, NULL, 5),
(13, 5, 'ご主人様', 1, NULL, 4),
(14, 5, '奥様', 2, NULL, 4),
(15, 5, 'その他のご家族', 3, NULL, 4),
(16, 1, '太陽光パネルと蓄電池の両方を導入', 1, 1, NULL),
(17, 1, '太陽光パネルのみ導入', 2, NULL, 5),
(18, 6, '聞き直し', 0, NULL, 6),
(19, 6, 'ガス給湯器 or 灯油ボイラーを使用', 1, NULL, 1),
(20, 6, 'エコキュート or 電気温水器を使用', 2, NULL, 1),
(21, 6, 'そのほかの給湯器を使用', 3, NULL, 1),
(22, 7, '聞き直し', 0, NULL, 7),
(23, 7, '７６歳以上', 1, 2, NULL),
(24, 7, '７０歳から７５歳', 2, NULL, 6),
(25, 7, '３０歳から６９歳', 3, NULL, 6),
(26, 7, '２９歳以下', 4, 2, NULL),
(27, 8, '聞き直し', 0, NULL, 8),
(28, 8, '１万円以上', 1, NULL, 7),
(29, 8, '１万円以下', 2, NULL, 7),
(30, 9, '聞き直し', 0, NULL, 9),
(31, 9, '一戸建て、持ち家', 1, NULL, 8),
(32, 9, '賃貸住宅', 2, NULL, 4),
(48, 18, '聞き直し', 0, NULL, 18),
(49, 18, 'vds', 1, NULL, 18);

INSERT INTO `reserves` (`id`, `survey_id`, `date`, `start`, `end`, `status`, `reserve_file`, `result_file`) VALUES
(1, 2, '2024-02-02', '09:00:00', '10:15:00', 0, '/storage/outputs/ac1_2024-02-02.json', NULL),
(2, 2, '2024-02-02', '14:20:38', '17:20:38', 0, '/storage/outputs/ac2_2024-02-02.json', NULL),
(3, 2, '2024-02-03', '09:00:00', '10:00:00', 1, '/storage/outputs/ac3_2024-02-03.json', NULL);

INSERT INTO `reserves_areas` (`id`, `reserve_id`, `area_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 3, 1),
(4, 3, 5);

INSERT INTO `send_emails` (`id`, `user_id`, `email`, `enabled`) VALUES
(1, 1, 'test2@example.com', 0);

INSERT INTO `surveys` (`id`, `user_id`, `title`, `note`, `greeting`, `greeting_voice_file`, `voice_name`) VALUES
(1, 1, 'アンケート１', '説明テキスト', NULL, NULL, 'ja-JP-Standard-A'),
(2, 1, 'アンケ２あ', 'アああ', 'こちらは、電力〇〇センターです。〇〇電力管内にお住まいの皆様へ、〇〇電力のお得なプランに切り替えた場合、\r\nどれくらい電気代が削減できるかの診断精査を行っております。\r\n１分程度の音声質問にご協力をお願いします。　尚、音声の途中でもご回答頂けます。\r\n', NULL, 'ja-JP-Standard-A');

INSERT INTO `users` (`id`, `email`, `password`, `status`) VALUES
(1, 'test@example.com', '$2y$10$smM.1r.LkbkvktimMdr14ufFph9Wb97w2t5/wZVuXCeW0z3MLi8iW', 0),
(2, 'test2@example.com', '$2y$10$smM.1r.LkbkvktimMdr14ufFph9Wb97w2t5/wZVuXCeW0z3MLi8iW', 0),
(3, 'admin@example.com', '$2y$10$smM.1r.LkbkvktimMdr14ufFph9Wb97w2t5/wZVuXCeW0z3MLi8iW', 1);

INSERT INTO `areas` (`id`, `title`) VALUES
  (1, '四国'),
  (2, '中国'),
  (3, '関西'),
  (4, '関東&甲信越'),
  (5, '東北'),
  (6, '九州'),
  (7, '中部'),
  (8, '関東&中部'),
  (9, '中国&九州&沖縄'),
  (10, '関東（東京）'),
  (11, '北海道'),
  (12, '北陸'),
  (13, '関東'),
  (14, '沖縄'),
  (15, '北海道&九州'),
  (16, '北陸&中国&九州'),
  (17, '中部&北陸'),
  (18, '東京&中部&関西'),
  (19, '中部&関西'),
  (20, '関西&関東'),
  (21, '関西&中部&東京'),
  (22, '中国&九州&北陸'),
  (23, '北海道&関西'),
  (24, '東北&九州&四国'),
  (25, '中国&四国&九州'),
  (26, '中国&関西'),
  (27, '東京&関西'),
  (28, '中部&東京');

INSERT INTO `stations` (`id`, `area_id`, `title`, `prefix`) VALUES
  (1, 1, '四国', '090-100'),
  (2, 2, '中国', '090-101'),
  (3, 3, '関西', '090-102'),
  (4, 4, '関東&甲信越', '090-103'),
  (5, 4, '関東&甲信越', '090-104'),
  (6, 4, '関東&甲信越', '090-105'),
  (7, 5, '東北', '090-106'),
  (8, 3, '関西', '090-107'),
  (9, 6, '九州', '090-108'),
  (10, 7, '中部', '090-109'),
  (11, 8, '関東&中部', '090-110'),
  (12, 4, '関東&甲信越', '090-111'),
  (13, 4, '関東&甲信越', '090-112'),
  (14, 3, '関西', '090-113'),
  (15, 3, '関西', '090-114'),
  (16, 3, '関西', '090-115'),
  (17, 6, '九州', '090-116'),
  (18, 9, '中国&九州&沖縄', '090-117'),
  (19, 2, '中国', '090-118'),
  (20, 6, '九州', '090-119'),
  (21, 10, '関東（東京）', '090-120'),
  (22, 10, '関東（東京）', '090-121'),
  (23, 3, '関西', '090-122'),
  (24, 7, '中部', '090-123'),
  (25, 3, '関西', '090-124'),
  (26, 10, '関東（東京）', '090-125'),
  (27, 10, '関東（東京）', '090-126'),
  (28, 7, '中部', '090-127'),
  (29, 7, '中部', '090-128'),
  (30, 7, '中部', '090-129'),
  (31, 11, '北海道', '090-130'),
  (32, 12, '北陸', '090-131'),
  (33, 1, '四国', '090-132'),
  (34, 2, '中国', '090-133'),
  (35, 6, '九州', '090-134'),
  (36, 2, '中国', '090-135'),
  (37, 6, '九州', '090-136'),
  (38, 5, '東北', '090-137'),
  (39, 11, '北海道', '090-138'),
  (40, 5, '東北', '090-139'),
  (41, 4, '関東&甲信越', '090-140'),
  (42, 7, '中部', '090-141'),
  (43, 4, '関東&甲信越', '090-142'),
  (44, 4, '関東&甲信越', '090-143'),
  (45, 3, '関西', '090-144'),
  (46, 4, '関東&甲信越', '090-145'),
  (47, 4, '関東&甲信越', '090-146'),
  (48, 7, '中部', '090-147'),
  (49, 3, '関西', '090-148'),
  (50, 5, '東北', '090-149'),
  (51, 4, '関東&甲信越', '090-150'),
  (52, 6, '九州', '090-151'),
  (53, 11, '北海道', '090-152'),
  (54, 4, '関東&甲信越', '090-153'),
  (55, 4, '関東&甲信越', '090-154'),
  (56, 4, '関東&甲信越', '090-155'),
  (57, 7, '中部', '090-156'),
  (58, 1, '四国', '090-157'),
  (59, 3, '関西', '090-158'),
  (60, 3, '関西', '090-159'),
  (61, 4, '関東&甲信越', '090-160'),
  (62, 4, '関東&甲信越', '090-161'),
  (63, 7, '中部', '090-162'),
  (64, 12, '北陸', '090-163'),
  (65, 11, '北海道', '090-164'),
  (66, 4, '関東&甲信越', '090-165'),
  (67, 4, '関東&甲信越', '090-166'),
  (68, 3, '関西', '090-167'),
  (69, 2, '中国', '090-168'),
  (70, 4, '関東&甲信越', '090-169'),
  (71, 10, '関東（東京）', '090-170'),
  (72, 3, '関西', '090-171'),
  (73, 7, '中部', '090-172'),
  (74, 10, '関東（東京）', '090-173'),
  (75, 7, '中部', '090-174'),
  (76, 7, '中部', '090-175'),
  (77, 10, '関東（東京）', '090-176'),
  (78, 10, '関東（東京）', '090-177'),
  (79, 7, '中部', '090-178'),
  (80, 7, '中部', '090-179'),
  (81, 13, '関東', '090-180'),
  (82, 13, '関東', '090-181'),
  (83, 7, '中部', '090-182'),
  (84, 8, '関東&中部', '090-183'),
  (85, 13, '関東', '090-184'),
  (86, 13, '関東', '090-185'),
  (87, 8, '関東&中部', '090-186'),
  (88, 6, '九州', '090-187'),
  (89, 4, '関東&甲信越', '090-188'),
  (90, 3, '関西', '090-189'),
  (91, 3, '関西', '090-190'),
  (92, 3, '関西', '090-191'),
  (93, 6, '九州', '090-192'),
  (94, 5, '東北', '090-193'),
  (95, 14, '沖縄', '090-194'),
  (96, 3, '関西', '090-195'),
  (97, 3, '関西', '090-196'),
  (98, 6, '九州', '090-197'),
  (99, 7, '中部', '090-198'),
  (100, 4, '関東&甲信越', '090-199'),
  (101, 2, '中国', '090-200'),
  (102, 3, '関西', '090-201'),
  (103, 5, '東北', '090-202'),
  (104, 12, '北陸', '090-203'),
  (105, 3, '関西', '090-204'),
  (106, 11, '北海道', '090-205'),
  (107, 3, '関西', '090-206'),
  (108, 15, '北海道&九州', '090-207'),
  (109, 6, '九州', '090-208'),
  (110, 16, '北陸&中国&九州', '090-209'),
  (111, 3, '関西', '090-210'),
  (112, 3, '関西', '090-211'),
  (113, 12, '北陸', '090-212'),
  (114, 7, '中部', '090-213'),
  (115, 4, '関東&甲信越', '090-214'),
  (116, 4, '関東&甲信越', '090-215'),
  (117, 4, '関東&甲信越', '090-216'),
  (118, 4, '関東&甲信越', '090-217'),
  (119, 7, '中部', '090-218'),
  (120, 3, '関西', '090-219'),
  (121, 4, '関東&甲信越', '090-220'),
  (122, 4, '関東&甲信越', '090-221'),
  (123, 4, '関東&甲信越', '090-222'),
  (124, 4, '関東&甲信越', '090-223'),
  (125, 4, '関東&甲信越', '090-224'),
  (126, 4, '関東&甲信越', '090-225'),
  (127, 7, '中部', '090-226'),
  (128, 5, '東北', '090-227'),
  (129, 3, '関西', '090-228'),
  (130, 2, '中国', '090-229'),
  (131, 4, '関東&甲信越', '090-230'),
  (132, 4, '関東&甲信越', '090-231'),
  (133, 4, '関東&甲信越', '090-232'),
  (134, 4, '関東&甲信越', '090-233'),
  (135, 7, '中部', '090-234'),
  (136, 3, '関西', '090-235'),
  (137, 5, '東北', '090-236'),
  (138, 12, '北陸', '090-237'),
  (139, 3, '関西', '090-238'),
  (140, 6, '九州', '090-239'),
  (141, 4, '関東&甲信越', '090-240'),
  (142, 4, '関東&甲信越', '090-241'),
  (143, 4, '関東&甲信越', '090-242'),
  (144, 4, '関東&甲信越', '090-243'),
  (145, 4, '関東&甲信越', '090-244'),
  (146, 4, '関東&甲信越', '090-245'),
  (147, 4, '関東&甲信越', '090-246'),
  (148, 4, '関東&甲信越', '090-247'),
  (149, 4, '関東&甲信越', '090-248'),
  (150, 4, '関東&甲信越', '090-249'),
  (151, 6, '九州', '090-250'),
  (152, 6, '九州', '090-251'),
  (153, 4, '関東&甲信越', '090-252'),
  (154, 4, '関東&甲信越', '090-253'),
  (155, 4, '関東&甲信越', '090-254'),
  (156, 4, '関東&甲信越', '090-255'),
  (157, 4, '関東&甲信越', '090-256'),
  (158, 7, '中部', '090-257'),
  (159, 6, '九州', '090-258'),
  (160, 3, '関西', '090-259'),
  (161, 5, '東北', '090-260'),
  (162, 7, '中部', '090-261'),
  (163, 4, '関東&甲信越', '090-262'),
  (164, 4, '関東&甲信越', '090-263'),
  (165, 4, '関東&甲信越', '090-264'),
  (166, 4, '関東&甲信越', '090-265'),
  (167, 4, '関東&甲信越', '090-266'),
  (168, 4, '関東&甲信越', '090-267'),
  (169, 7, '中部', '090-268'),
  (170, 11, '北海道', '090-269'),
  (171, 3, '関西', '090-270'),
  (172, 6, '九州', '090-271'),
  (173, 4, '関東&甲信越', '090-272'),
  (174, 4, '関東&甲信越', '090-273'),
  (175, 4, '関東&甲信越', '090-274'),
  (176, 4, '関東&甲信越', '090-275'),
  (177, 4, '関東&甲信越', '090-276'),
  (178, 7, '中部', '090-277'),
  (179, 1, '四国', '090-278'),
  (180, 5, '東北', '090-279'),
  (181, 2, '中国', '090-280'),
  (182, 11, '北海道', '090-281'),
  (183, 1, '四国', '090-282'),
  (184, 12, '北陸', '090-283'),
  (185, 5, '東北', '090-284'),
  (186, 6, '九州', '090-285'),
  (187, 2, '中国', '090-286'),
  (188, 11, '北海道', '090-287'),
  (189, 5, '東北', '090-288'),
  (190, 1, '四国', '090-289'),
  (191, 10, '関東（東京）', '090-290'),
  (192, 10, '関東（東京）', '090-291'),
  (193, 7, '中部', '090-292'),
  (194, 13, '関東', '090-293'),
  (195, 8, '関東&中部', '090-294'),
  (196, 5, '東北', '090-295'),
  (197, 6, '九州', '090-296'),
  (198, 5, '東北', '090-297'),
  (199, 5, '東北', '090-298'),
  (200, 5, '東北', '090-299'),
  (201, 4, '関東&甲信越', '090-300'),
  (202, 15, '北海道&九州', '090-301'),
  (203, 4, '関東&甲信越', '090-302'),
  (204, 3, '関西', '090-303'),
  (205, 4, '関東&甲信越', '090-304'),
  (206, 3, '関西', '090-305'),
  (207, 4, '関東&甲信越', '090-306'),
  (208, 6, '九州', '090-307'),
  (209, 4, '関東&甲信越', '090-308'),
  (210, 4, '関東&甲信越', '090-309'),
  (211, 4, '関東&甲信越', '090-310'),
  (212, 11, '北海道', '090-311'),
  (213, 5, '東北', '090-312'),
  (214, 4, '関東&甲信越', '090-313'),
  (215, 4, '関東&甲信越', '090-314'),
  (216, 17, '中部&北陸', '090-315'),
  (217, 3, '関西', '090-316'),
  (218, 2, '中国', '090-317'),
  (219, 1, '四国', '090-318'),
  (220, 6, '九州', '090-319'),
  (221, 4, '関東&甲信越', '090-320'),
  (222, 4, '関東&甲信越', '090-321'),
  (223, 4, '関東&甲信越', '090-322'),
  (224, 4, '関東&甲信越', '090-323'),
  (225, 4, '関東&甲信越', '090-324'),
  (226, 7, '中部', '090-325'),
  (227, 3, '関西', '090-326'),
  (228, 3, '関西', '090-327'),
  (229, 3, '関西', '090-328'),
  (230, 12, '北陸', '090-329'),
  (231, 7, '中部', '090-330'),
  (232, 4, '関東&甲信越', '090-331'),
  (233, 6, '九州', '090-332'),
  (234, 4, '関東&甲信越', '090-333'),
  (235, 4, '関東&甲信越', '090-334'),
  (236, 3, '関西', '090-335'),
  (237, 5, '東北', '090-336'),
  (238, 2, '中国', '090-337'),
  (239, 7, '中部', '090-338'),
  (240, 11, '北海道', '090-339'),
  (241, 4, '関東&甲信越', '090-340'),
  (242, 6, '九州', '090-341'),
  (243, 18, '東京&中部&関西', '090-342'),
  (244, 13, '関東', '090-343'),
  (245, 7, '中部', '090-344'),
  (246, 8, '関東&中部', '090-345'),
  (247, 1, '四国', '090-346'),
  (248, 4, '関東&甲信越', '090-347'),
  (249, 19, '中部&関西', '090-348'),
  (250, 20, '関西&関東', '090-349'),
  (251, 13, '関東', '090-350'),
  (252, 13, '関東', '090-351'),
  (253, 13, '関東', '090-352'),
  (254, 13, '関東', '090-353'),
  (255, 13, '関東', '090-354'),
  (256, 7, '中部', '090-355'),
  (257, 7, '中部', '090-356'),
  (258, 8, '関東&中部', '090-357'),
  (259, 7, '中部', '090-358'),
  (260, 13, '関東', '090-359'),
  (261, 6, '九州', '090-360'),
  (262, 3, '関西', '090-361'),
  (263, 3, '関西', '090-362'),
  (264, 2, '中国', '090-363'),
  (265, 5, '東北', '090-364'),
  (266, 3, '関西', '090-365'),
  (267, 6, '九州', '090-366'),
  (268, 3, '関西', '090-367'),
  (269, 13, '関東', '090-368'),
  (270, 13, '関東', '090-369'),
  (271, 3, '関西', '090-370'),
  (272, 3, '関西', '090-371'),
  (273, 3, '関西', '090-372'),
  (274, 6, '九州', '090-373'),
  (275, 2, '中国', '090-374'),
  (276, 5, '東北', '090-375'),
  (277, 12, '北陸', '090-376'),
  (278, 11, '北海道', '090-377'),
  (279, 1, '四国', '090-378'),
  (280, 14, '沖縄', '090-379'),
  (281, 10, '関東（東京）', '090-380'),
  (282, 10, '関東（東京）', '090-381'),
  (283, 3, '関西', '090-382'),
  (284, 7, '中部', '090-383'),
  (285, 3, '関西', '090-384'),
  (286, 19, '中部&関西', '090-385'),
  (287, 3, '関西', '090-386'),
  (288, 21, '関西&中部&東京', '090-387'),
  (289, 22, '中国&九州&北陸', '090-388'),
  (290, 23, '北海道&関西', '090-389'),
  (291, 10, '関東（東京）', '090-390'),
  (292, 10, '関東（東京）', '090-391'),
  (293, 3, '関西', '090-392'),
  (294, 7, '中部', '090-393'),
  (295, 3, '関西', '090-394'),
  (296, 7, '中部', '090-395'),
  (297, 21, '関西&中部&東京', '090-396'),
  (298, 3, '関西', '090-397'),
  (299, 24, '東北&九州&四国', '090-398'),
  (300, 3, '関西', '090-399'),
  (301, 4, '関東&甲信越', '090-400'),
  (302, 4, '関東&甲信越', '090-401'),
  (303, 4, '関東&甲信越', '090-402'),
  (304, 3, '関西', '090-403'),
  (305, 5, '東北', '090-404'),
  (306, 4, '関東&甲信越', '090-405'),
  (307, 4, '関東&甲信越', '090-406'),
  (308, 4, '関東&甲信越', '090-407'),
  (309, 7, '中部', '090-408'),
  (310, 4, '関東&甲信越', '090-409'),
  (311, 2, '中国', '090-410'),
  (312, 7, '中部', '090-411'),
  (313, 4, '関東&甲信越', '090-412'),
  (314, 4, '関東&甲信越', '090-413'),
  (315, 2, '中国', '090-414'),
  (316, 7, '中部', '090-415'),
  (317, 8, '関東&中部', '090-416'),
  (318, 13, '関東', '090-417'),
  (319, 7, '中部', '090-418'),
  (320, 7, '中部', '090-419'),
  (321, 13, '関東', '090-420'),
  (322, 7, '中部', '090-421'),
  (323, 8, '関東&中部', '090-422'),
  (324, 7, '中部', '090-423'),
  (325, 13, '関東', '090-424'),
  (326, 7, '中部', '090-425'),
  (327, 7, '中部', '090-426'),
  (328, 3, '関西', '090-427'),
  (329, 3, '関西', '090-428'),
  (330, 3, '関西', '090-429'),
  (331, 3, '関西', '090-430'),
  (332, 5, '東北', '090-431'),
  (333, 12, '北陸', '090-432'),
  (334, 2, '中国', '090-433'),
  (335, 6, '九州', '090-434'),
  (336, 6, '九州', '090-435'),
  (337, 4, '関東&甲信越', '090-436'),
  (338, 4, '関東&甲信越', '090-437'),
  (339, 4, '関東&甲信越', '090-438'),
  (340, 4, '関東&甲信越', '090-439'),
  (341, 7, '中部', '090-440'),
  (342, 10, '関東（東京）', '090-441'),
  (343, 13, '関東', '090-442'),
  (344, 13, '関東', '090-443'),
  (345, 7, '中部', '090-444'),
  (346, 13, '関東', '090-445'),
  (347, 7, '中部', '090-446'),
  (348, 14, '沖縄', '090-447'),
  (349, 6, '九州', '090-448'),
  (350, 3, '関西', '090-449'),
  (351, 1, '四国', '090-450'),
  (352, 6, '九州', '090-451'),
  (353, 4, '関東&甲信越', '090-452'),
  (354, 4, '関東&甲信越', '090-453'),
  (355, 4, '関東&甲信越', '090-454'),
  (356, 5, '東北', '090-455'),
  (357, 3, '関西', '090-456'),
  (358, 2, '中国', '090-457'),
  (359, 6, '九州', '090-458'),
  (360, 4, '関東&甲信越', '090-459'),
  (361, 4, '関東&甲信越', '090-460'),
  (362, 4, '関東&甲信越', '090-461'),
  (363, 4, '関東&甲信越', '090-462'),
  (364, 5, '東北', '090-463'),
  (365, 3, '関西', '090-464'),
  (366, 2, '中国', '090-465'),
  (367, 4, '関東&甲信越', '090-466'),
  (368, 4, '関東&甲信越', '090-467'),
  (369, 12, '北陸', '090-468'),
  (370, 2, '中国', '090-469'),
  (371, 4, '関東&甲信越', '090-470'),
  (372, 4, '関東&甲信越', '090-471'),
  (373, 4, '関東&甲信越', '090-472'),
  (374, 4, '関東&甲信越', '090-473'),
  (375, 4, '関東&甲信越', '090-474'),
  (376, 4, '関東&甲信越', '090-475'),
  (377, 3, '関西', '090-476'),
  (378, 6, '九州', '090-477'),
  (379, 1, '四国', '090-478'),
  (380, 7, '中部', '090-479'),
  (381, 2, '中国', '090-480'),
  (382, 4, '関東&甲信越', '090-481'),
  (383, 4, '関東&甲信越', '090-482'),
  (384, 4, '関東&甲信越', '090-483'),
  (385, 4, '関東&甲信越', '090-484'),
  (386, 7, '中部', '090-485'),
  (387, 7, '中部', '090-486'),
  (388, 11, '北海道', '090-487'),
  (389, 5, '東北', '090-488'),
  (390, 2, '中国', '090-489'),
  (391, 3, '関西', '090-490'),
  (392, 4, '関東&甲信越', '090-491'),
  (393, 4, '関東&甲信越', '090-492'),
  (394, 4, '関東&甲信越', '090-493'),
  (395, 4, '関東&甲信越', '090-494'),
  (396, 4, '関東&甲信越', '090-495'),
  (397, 4, '関東&甲信越', '090-496'),
  (398, 1, '四国', '090-497'),
  (399, 6, '九州', '090-498'),
  (400, 6, '九州', '090-499'),
  (401, 7, '中部', '090-500'),
  (402, 3, '関西', '090-501'),
  (403, 6, '九州', '090-502'),
  (404, 7, '中部', '090-503'),
  (405, 3, '関西', '090-504'),
  (406, 3, '関西', '090-505'),
  (407, 3, '関西', '090-506'),
  (408, 11, '北海道', '090-507'),
  (409, 6, '九州', '090-508'),
  (410, 3, '関西', '090-509'),
  (411, 7, '中部', '090-510'),
  (412, 7, '中部', '090-511'),
  (413, 3, '関西', '090-512'),
  (414, 3, '関西', '090-513'),
  (415, 1, '四国', '090-514'),
  (416, 3, '関西', '090-515'),
  (417, 3, '関西', '090-516'),
  (418, 12, '北陸', '090-517'),
  (419, 5, '東北', '090-518'),
  (420, 4, '関東&甲信越', '090-519'),
  (421, 4, '関東&甲信越', '090-520'),
  (422, 4, '関東&甲信越', '090-521'),
  (423, 11, '北海道', '090-522'),
  (424, 5, '東北', '090-523'),
  (425, 3, '関西', '090-524'),
  (426, 3, '関西', '090-525'),
  (427, 2, '中国', '090-526'),
  (428, 1, '四国', '090-527'),
  (429, 6, '九州', '090-528'),
  (430, 6, '九州', '090-529'),
  (431, 4, '関東&甲信越', '090-530'),
  (432, 4, '関東&甲信越', '090-531'),
  (433, 4, '関東&甲信越', '090-532'),
  (434, 4, '関東&甲信越', '090-533'),
  (435, 4, '関東&甲信越', '090-534'),
  (436, 5, '東北', '090-535'),
  (437, 3, '関西', '090-536'),
  (438, 2, '中国', '090-537'),
  (439, 6, '九州', '090-538'),
  (440, 4, '関東&甲信越', '090-539'),
  (441, 4, '関東&甲信越', '090-540'),
  (442, 4, '関東&甲信越', '090-541'),
  (443, 4, '関東&甲信越', '090-542'),
  (444, 4, '関東&甲信越', '090-543'),
  (445, 4, '関東&甲信越', '090-544'),
  (446, 7, '中部', '090-545'),
  (447, 3, '関西', '090-546'),
  (448, 6, '九州', '090-547'),
  (449, 6, '九州', '090-548'),
  (450, 4, '関東&甲信越', '090-549'),
  (451, 4, '関東&甲信越', '090-550'),
  (452, 4, '関東&甲信越', '090-551'),
  (453, 4, '関東&甲信越', '090-552'),
  (454, 4, '関東&甲信越', '090-553'),
  (455, 4, '関東&甲信越', '090-554'),
  (456, 4, '関東&甲信越', '090-555'),
  (457, 4, '関東&甲信越', '090-556'),
  (458, 4, '関東&甲信越', '090-557'),
  (459, 4, '関東&甲信越', '090-558'),
  (460, 5, '東北', '090-559'),
  (461, 7, '中部', '090-560'),
  (462, 7, '中部', '090-561'),
  (463, 7, '中部', '090-562'),
  (464, 7, '中部', '090-563'),
  (465, 3, '関西', '090-564'),
  (466, 3, '関西', '090-565'),
  (467, 3, '関西', '090-566'),
  (468, 3, '関西', '090-567'),
  (469, 12, '北陸', '090-568'),
  (470, 2, '中国', '090-569'),
  (471, 2, '中国', '090-570'),
  (472, 1, '四国', '090-571'),
  (473, 6, '九州', '090-572'),
  (474, 6, '九州', '090-573'),
  (475, 6, '九州', '090-574'),
  (476, 4, '関東&甲信越', '090-575'),
  (477, 4, '関東&甲信越', '090-576'),
  (478, 4, '関東&甲信越', '090-577'),
  (479, 4, '関東&甲信越', '090-578'),
  (480, 4, '関東&甲信越', '090-579'),
  (481, 4, '関東&甲信越', '090-580'),
  (482, 4, '関東&甲信越', '090-581'),
  (483, 4, '関東&甲信越', '090-582'),
  (484, 5, '東北', '090-583'),
  (485, 5, '東北', '090-584'),
  (486, 7, '中部', '090-585'),
  (487, 7, '中部', '090-586'),
  (488, 7, '中部', '090-587'),
  (489, 3, '関西', '090-588'),
  (490, 3, '関西', '090-589'),
  (491, 3, '関西', '090-590'),
  (492, 2, '中国', '090-591'),
  (493, 4, '関東&甲信越', '090-592'),
  (494, 4, '関東&甲信越', '090-593'),
  (495, 4, '関東&甲信越', '090-594'),
  (496, 11, '北海道', '090-595'),
  (497, 3, '関西', '090-596'),
  (498, 3, '関西', '090-597'),
  (499, 11, '北海道', '090-598'),
  (500, 4, '関東&甲信越', '090-599'),
  (501, 11, '北海道', '090-669'),
  (502, 7, '中部', '090-676'),
  (503, 6, '九州', '090-677'),
  (504, 5, '東北', '090-678'),
  (505, 7, '中部', '090-680'),
  (506, 12, '北陸', '090-681'),
  (507, 3, '関西', '090-682'),
  (508, 2, '中国', '090-684'),
  (509, 14, '沖縄', '090-686'),
  (510, 1, '四国', '090-688'),
  (511, 6, '九州', '090-689'),
  (512, 3, '関西', '090-690'),
  (513, 3, '関西', '090-691'),
  (514, 3, '関西', '090-696'),
  (515, 3, '関西', '090-697'),
  (516, 3, '関西', '090-698'),
  (517, 11, '北海道', '090-699'),
  (518, 4, '関東&甲信越', '090-700'),
  (519, 4, '関東&甲信越', '090-701'),
  (520, 7, '中部', '090-702'),
  (521, 7, '中部', '090-703'),
  (522, 7, '中部', '090-704'),
  (523, 11, '北海道', '090-705'),
  (524, 5, '東北', '090-706'),
  (525, 5, '東北', '090-707'),
  (526, 12, '北陸', '090-708'),
  (527, 3, '関西', '090-709'),
  (528, 3, '関西', '090-710'),
  (529, 3, '関西', '090-711'),
  (530, 2, '中国', '090-712'),
  (531, 2, '中国', '090-713'),
  (532, 1, '四国', '090-714'),
  (533, 6, '九州', '090-715'),
  (534, 6, '九州', '090-716'),
  (535, 4, '関東&甲信越', '090-717'),
  (536, 4, '関東&甲信越', '090-718'),
  (537, 4, '関東&甲信越', '090-719'),
  (538, 4, '関東&甲信越', '090-720'),
  (539, 4, '関東&甲信越', '090-721'),
  (540, 4, '関東&甲信越', '090-722'),
  (541, 4, '関東&甲信越', '090-723'),
  (542, 4, '関東&甲信越', '090-724'),
  (543, 4, '関東&甲信越', '090-725'),
  (544, 4, '関東&甲信越', '090-726'),
  (545, 4, '関東&甲信越', '090-727'),
  (546, 4, '関東&甲信越', '090-728'),
  (547, 6, '九州', '090-729'),
  (548, 7, '中部', '090-730'),
  (549, 7, '中部', '090-731'),
  (550, 5, '東北', '090-732'),
  (551, 5, '東北', '090-733'),
  (552, 3, '関西', '090-734'),
  (553, 3, '関西', '090-735'),
  (554, 3, '関西', '090-736'),
  (555, 2, '中国', '090-737'),
  (556, 6, '九州', '090-738'),
  (557, 6, '九州', '090-739'),
  (558, 4, '関東&甲信越', '090-740'),
  (559, 4, '関東&甲信越', '090-741'),
  (560, 4, '関東&甲信越', '090-742'),
  (561, 7, '中部', '090-743'),
  (562, 6, '九州', '090-744'),
  (563, 6, '九州', '090-745'),
  (564, 6, '九州', '090-746'),
  (565, 6, '九州', '090-747'),
  (566, 3, '関西', '090-748'),
  (567, 3, '関西', '090-749'),
  (568, 2, '中国', '090-750'),
  (569, 11, '北海道', '090-751'),
  (570, 5, '東北', '090-752'),
  (571, 6, '九州', '090-753'),
  (572, 2, '中国', '090-754'),
  (573, 3, '関西', '090-755'),
  (574, 5, '東北', '090-756'),
  (575, 1, '四国', '090-757'),
  (576, 12, '北陸', '090-758'),
  (577, 2, '中国', '090-759'),
  (578, 7, '中部', '090-760'),
  (579, 7, '中部', '090-761'),
  (580, 1, '四国', '090-762'),
  (581, 4, '関東&甲信越', '090-763'),
  (582, 11, '北海道', '090-764'),
  (583, 11, '北海道', '090-765'),
  (584, 5, '東北', '090-766'),
  (585, 7, '中部', '090-767'),
  (586, 7, '中部', '090-768'),
  (587, 7, '中部', '090-769'),
  (588, 4, '関東&甲信越', '090-770'),
  (589, 4, '関東&甲信越', '090-771'),
  (590, 4, '関東&甲信越', '090-772'),
  (591, 4, '関東&甲信越', '090-773'),
  (592, 12, '北陸', '090-774'),
  (593, 3, '関西', '090-775'),
  (594, 3, '関西', '090-776'),
  (595, 2, '中国', '090-777'),
  (596, 1, '四国', '090-778'),
  (597, 5, '東北', '090-779'),
  (598, 4, '関東&甲信越', '090-780'),
  (599, 4, '関東&甲信越', '090-781'),
  (600, 4, '関東&甲信越', '090-782'),
  (601, 4, '関東&甲信越', '090-783'),
  (602, 4, '関東&甲信越', '090-784'),
  (603, 7, '中部', '090-785'),
  (604, 7, '中部', '090-786'),
  (605, 3, '関西', '090-787'),
  (606, 3, '関西', '090-788'),
  (607, 2, '中国', '090-789'),
  (608, 4, '関東&甲信越', '090-790'),
  (609, 7, '中部', '090-791'),
  (610, 6, '九州', '090-792'),
  (611, 5, '東北', '090-793'),
  (612, 4, '関東&甲信越', '090-794'),
  (613, 7, '中部', '090-795'),
  (614, 3, '関西', '090-796'),
  (615, 2, '中国', '090-797'),
  (616, 6, '九州', '090-798'),
  (617, 2, '中国', '090-799'),
  (618, 4, '関東&甲信越', '090-800'),
  (619, 4, '関東&甲信越', '090-801'),
  (620, 4, '関東&甲信越', '090-802'),
  (621, 4, '関東&甲信越', '090-803'),
  (622, 13, '関東', '090-804'),
  (623, 13, '関東', '090-805'),
  (624, 2, '中国', '090-806'),
  (625, 7, '中部', '090-807'),
  (626, 10, '関東（東京）', '090-808'),
  (627, 12, '北陸', '090-809'),
  (628, 10, '関東（東京）', '090-810'),
  (629, 10, '関東（東京）', '090-811'),
  (630, 3, '関西', '090-812'),
  (631, 7, '中部', '090-813'),
  (632, 3, '関西', '090-814'),
  (633, 7, '中部', '090-815'),
  (634, 3, '関西', '090-816'),
  (635, 10, '関東（東京）', '090-817'),
  (636, 7, '中部', '090-818'),
  (637, 3, '関西', '090-819'),
  (638, 3, '関西', '090-820'),
  (639, 3, '関西', '090-821'),
  (640, 6, '九州', '090-822'),
  (641, 3, '関西', '090-823'),
  (642, 2, '中国', '090-824'),
  (643, 5, '東北', '090-825'),
  (644, 12, '北陸', '090-826'),
  (645, 11, '北海道', '090-827'),
  (646, 25, '中国&四国&九州', '090-828'),
  (647, 14, '沖縄', '090-829'),
  (648, 13, '関東', '090-830'),
  (649, 13, '関東', '090-831'),
  (650, 7, '中部', '090-832'),
  (651, 8, '関東&中部', '090-833'),
  (652, 10, '関東（東京）', '090-834'),
  (653, 6, '九州', '090-835'),
  (654, 26, '中国&関西', '090-836'),
  (655, 23, '北海道&関西', '090-837'),
  (656, 3, '関西', '090-838'),
  (657, 6, '九州', '090-839'),
  (658, 6, '九州', '090-840'),
  (659, 6, '九州', '090-841'),
  (660, 7, '中部', '090-842'),
  (661, 10, '関東（東京）', '090-843'),
  (662, 27, '東京&関西', '090-844'),
  (663, 10, '関東（東京）', '090-845'),
  (664, 10, '関東（東京）', '090-846'),
  (665, 7, '中部', '090-847'),
  (666, 3, '関西', '090-848'),
  (667, 10, '関東（東京）', '090-849'),
  (668, 10, '関東（東京）', '090-850'),
  (669, 10, '関東（東京）', '090-851'),
  (670, 3, '関西', '090-852'),
  (671, 3, '関西', '090-853'),
  (672, 7, '中部', '090-854'),
  (673, 28, '中部&東京', '090-855'),
  (674, 10, '関東（東京）', '090-856'),
  (675, 3, '関西', '090-857'),
  (676, 4, '関東&甲信越', '090-858'),
  (677, 4, '関東&甲信越', '090-859'),
  (678, 2, '中国', '090-860'),
  (679, 5, '東北', '090-861'),
  (680, 6, '九州', '090-862'),
  (681, 11, '北海道', '090-863'),
  (682, 4, '関東&甲信越', '090-864'),
  (683, 3, '関西', '090-865'),
  (684, 6, '九州', '090-866'),
  (685, 7, '中部', '090-867'),
  (686, 4, '関東&甲信越', '090-868'),
  (687, 1, '四国', '090-869'),
  (688, 4, '関東&甲信越', '090-870'),
  (689, 2, '中国', '090-871'),
  (690, 4, '関東&甲信越', '090-872'),
  (691, 7, '中部', '090-873'),
  (692, 4, '関東&甲信越', '090-874'),
  (693, 3, '関西', '090-875'),
  (694, 6, '九州', '090-876'),
  (695, 4, '関東&甲信越', '090-877'),
  (696, 5, '東北', '090-878'),
  (697, 3, '関西', '090-879'),
  (698, 4, '関東&甲信越', '090-880'),
  (699, 4, '関東&甲信越', '090-881'),
  (700, 3, '関西', '090-882'),
  (701, 6, '九州', '090-883'),
  (702, 4, '関東&甲信越', '090-884'),
  (703, 4, '関東&甲信越', '090-885'),
  (704, 7, '中部', '090-886'),
  (705, 4, '関東&甲信越', '090-887'),
  (706, 3, '関西', '090-888'),
  (707, 11, '北海道', '090-889'),
  (708, 11, '北海道', '090-890'),
  (709, 6, '九州', '090-891'),
  (710, 5, '東北', '090-892'),
  (711, 3, '関西', '090-893'),
  (712, 4, '関東&甲信越', '090-894'),
  (713, 7, '中部', '090-895'),
  (714, 12, '北陸', '090-896'),
  (715, 1, '四国', '090-897'),
  (716, 3, '関西', '090-898'),
  (717, 2, '中国', '090-899'),
  (718, 4, '関東&甲信越', '090-900'),
  (719, 4, '関東&甲信越', '090-901'),
  (720, 7, '中部', '090-902'),
  (721, 5, '東北', '090-903'),
  (722, 3, '関西', '090-904'),
  (723, 3, '関西', '090-905'),
  (724, 2, '中国', '090-906'),
  (725, 6, '九州', '090-907'),
  (726, 11, '北海道', '090-908'),
  (727, 3, '関西', '090-909'),
  (728, 10, '関東（東京）', '090-910'),
  (729, 3, '関西', '090-911'),
  (730, 7, '中部', '090-912'),
  (731, 10, '関東（東京）', '090-913'),
  (732, 10, '関東（東京）', '090-914'),
  (733, 10, '関東（東京）', '090-915'),
  (734, 3, '関西', '090-916'),
  (735, 7, '中部', '090-917'),
  (736, 7, '中部', '090-918'),
  (737, 7, '中部', '090-919'),
  (738, 10, '関東（東京）', '090-920'),
  (739, 3, '関西', '090-921'),
  (740, 7, '中部', '090-922'),
  (741, 10, '関東（東京）', '090-923'),
  (742, 10, '関東（東京）', '090-924'),
  (743, 3, '関西', '090-925'),
  (744, 7, '中部', '090-926'),
  (745, 3, '関西', '090-927'),
  (746, 3, '関西', '090-928'),
  (747, 10, '関東（東京）', '090-929'),
  (748, 13, '関東', '090-930'),
  (749, 13, '関東', '090-931'),
  (750, 13, '関東', '090-932'),
  (751, 7, '中部', '090-933'),
  (752, 7, '中部', '090-934'),
  (753, 7, '中部', '090-935'),
  (754, 13, '関東', '090-936'),
  (755, 13, '関東', '090-937'),
  (756, 13, '関東', '090-938'),
  (757, 13, '関東', '090-939'),
  (758, 6, '九州', '090-940'),
  (759, 2, '中国', '090-941'),
  (760, 5, '東北', '090-942'),
  (761, 11, '北海道', '090-943'),
  (762, 12, '北陸', '090-944'),
  (763, 1, '四国', '090-945'),
  (764, 2, '中国', '090-946'),
  (765, 6, '九州', '090-947'),
  (766, 6, '九州', '090-948'),
  (767, 6, '九州', '090-949'),
  (768, 2, '中国', '090-950'),
  (769, 11, '北海道', '090-951'),
  (770, 11, '北海道', '090-952'),
  (771, 5, '東北', '090-953'),
  (772, 3, '関西', '090-954'),
  (773, 1, '四国', '090-955'),
  (774, 6, '九州', '090-956'),
  (775, 6, '九州', '090-957'),
  (776, 6, '九州', '090-958'),
  (777, 6, '九州', '090-959'),
  (778, 6, '九州', '090-960'),
  (779, 3, '関西', '090-961'),
  (780, 3, '関西', '090-962'),
  (781, 5, '東北', '090-963'),
  (782, 28, '中部&東京', '090-964'),
  (783, 6, '九州', '090-965'),
  (784, 7, '中部', '090-966'),
  (785, 13, '関東', '090-967'),
  (786, 10, '関東（東京）', '090-968'),
  (787, 3, '関西', '090-969'),
  (788, 3, '関西', '090-970'),
  (789, 3, '関西', '090-971'),
  (790, 6, '九州', '090-972'),
  (791, 2, '中国', '090-973'),
  (792, 5, '東北', '090-974'),
  (793, 11, '北海道', '090-975'),
  (794, 12, '北陸', '090-976'),
  (795, 1, '四国', '090-977'),
  (796, 14, '沖縄', '090-978'),
  (797, 6, '九州', '090-979'),
  (798, 10, '関東（東京）', '090-980'),
  (799, 10, '関東（東京）', '090-981'),
  (800, 10, '関東（東京）', '090-982'),
  (801, 10, '関東（東京）', '090-983'),
  (802, 10, '関東（東京）', '090-984'),
  (803, 10, '関東（東京）', '090-985'),
  (804, 3, '関西', '090-986'),
  (805, 3, '関西', '090-987'),
  (806, 3, '関西', '090-988'),
  (807, 7, '中部', '090-989'),
  (808, 7, '中部', '090-990'),
  (809, 7, '中部', '090-991'),
  (810, 7, '中部', '090-992'),
  (811, 7, '中部', '090-993'),
  (812, 7, '中部', '090-994'),
  (813, 10, '関東（東京）', '090-995'),
  (814, 10, '関東（東京）', '090-996'),
  (815, 10, '関東（東京）', '090-997'),
  (816, 3, '関西', '090-998'),
  (817, 3, '関西', '090-999'),
  (818, 3, '関西', '080-140'),
  (819, 3, '関西', '080-141'),
  (820, 3, '関西', '080-142'),
  (821, 3, '関西', '080-143'),
  (822, 3, '関西', '080-144'),
  (823, 3, '関西', '080-145'),
  (824, 3, '関西', '080-146'),
  (825, 3, '関西', '080-147'),
  (826, 3, '関西', '080-148'),
  (827, 3, '関西', '080-149'),
  (828, 3, '関西', '080-150'),
  (829, 3, '関西', '080-151'),
  (830, 3, '関西', '080-152'),
  (831, 6, '九州', '080-153'),
  (832, 6, '九州', '080-154'),
  (833, 7, '中部', '080-155'),
  (834, 7, '中部', '080-156'),
  (835, 7, '中部', '080-157'),
  (836, 7, '中部', '080-158'),
  (837, 7, '中部', '080-159'),
  (838, 7, '中部', '080-160'),
  (839, 7, '中部', '080-161'),
  (840, 7, '中部', '080-162'),
  (841, 2, '中国', '080-163'),
  (842, 2, '中国', '080-164'),
  (843, 5, '東北', '080-165'),
  (844, 5, '東北', '080-166'),
  (845, 5, '東北', '080-167'),
  (846, 5, '東北', '080-168'),
  (847, 5, '東北', '080-169'),
  (848, 6, '九州', '080-170'),
  (849, 6, '九州', '080-171'),
  (850, 6, '九州', '080-172'),
  (851, 6, '九州', '080-173'),
  (852, 6, '九州', '080-174'),
  (853, 6, '九州', '080-175'),
  (854, 6, '九州', '080-176'),
  (855, 6, '九州', '080-177'),
  (856, 6, '九州', '080-178'),
  (857, 6, '九州', '080-179'),
  (858, 5, '東北', '080-180'),
  (859, 5, '東北', '080-181'),
  (860, 5, '東北', '080-182'),
  (861, 5, '東北', '080-183'),
  (862, 5, '東北', '080-184'),
  (863, 5, '東北', '080-185'),
  (864, 11, '北海道', '080-186'),
  (865, 11, '北海道', '080-187'),
  (866, 11, '北海道', '080-188'),
  (867, 11, '北海道', '080-189'),
  (868, 2, '中国', '080-190'),
  (869, 2, '中国', '080-191'),
  (870, 2, '中国', '080-192'),
  (871, 2, '中国', '080-193'),
  (872, 2, '中国', '080-194'),
  (873, 12, '北陸', '080-195'),
  (874, 12, '北陸', '080-196'),
  (875, 11, '北海道', '080-197'),
  (876, 11, '北海道', '080-198'),
  (877, 1, '四国', '080-199'),
  (878, 3, '関西', '080-240'),
  (879, 3, '関西', '080-241'),
  (880, 3, '関西', '080-242'),
  (881, 3, '関西', '080-243'),
  (882, 3, '関西', '080-244'),
  (883, 3, '関西', '080-245'),
  (884, 3, '関西', '080-246'),
  (885, 3, '関西', '080-247'),
  (886, 3, '関西', '080-248'),
  (887, 3, '関西', '080-249'),
  (888, 3, '関西', '080-250'),
  (889, 3, '関西', '080-251'),
  (890, 3, '関西', '080-252'),
  (891, 3, '関西', '080-253'),
  (892, 3, '関西', '080-254'),
  (893, 7, '中部', '080-260'),
  (894, 7, '中部', '080-261'),
  (895, 7, '中部', '080-262'),
  (896, 7, '中部', '080-263'),
  (897, 7, '中部', '080-264'),
  (898, 7, '中部', '080-265'),
  (899, 7, '中部', '080-266'),
  (900, 6, '九州', '080-269'),
  (901, 6, '九州', '080-270'),
  (902, 6, '九州', '080-271'),
  (903, 6, '九州', '080-272'),
  (904, 6, '九州', '080-273'),
  (905, 6, '九州', '080-274'),
  (906, 6, '九州', '080-275'),
  (907, 6, '九州', '080-276'),
  (908, 6, '九州', '080-277'),
  (909, 6, '九州', '080-278'),
  (910, 6, '九州', '080-279'),
  (911, 5, '東北', '080-280'),
  (912, 5, '東北', '080-281'),
  (913, 5, '東北', '080-282'),
  (914, 5, '東北', '080-283'),
  (915, 5, '東北', '080-284'),
  (916, 1, '四国', '080-285'),
  (917, 11, '北海道', '080-286'),
  (918, 11, '北海道', '080-287'),
  (919, 2, '中国', '080-288'),
  (920, 2, '中国', '080-289'),
  (921, 2, '中国', '080-290'),
  (922, 2, '中国', '080-291'),
  (923, 2, '中国', '080-292'),
  (924, 2, '中国', '080-293'),
  (925, 2, '中国', '080-294'),
  (926, 12, '北陸', '080-295'),
  (927, 12, '北陸', '080-296'),
  (928, 2, '中国', '080-297'),
  (929, 2, '中国', '080-298'),
  (930, 2, '中国', '080-299'),
  (931, 14, '沖縄', '080-648'),
  (932, 14, '沖縄', '080-649'),
  (933, 5, '東北', '080-820'),
  (934, 5, '東北', '080-821'),
  (935, 7, '中部', '080-825'),
  (936, 3, '関西', '080-830'),
  (937, 3, '関西', '080-831'),
  (938, 6, '九州', '080-835'),
  (939, 6, '九州', '080-836'),
  (940, 6, '九州', '080-837'),
  (941, 14, '沖縄', '080-985');

SET FOREIGN_KEY_CHECKS = 1;