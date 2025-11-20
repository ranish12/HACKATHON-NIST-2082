-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 20, 2025 at 01:49 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sambodhan`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `title`, `content`, `created_at`) VALUES
(1, 'The Importance of Mental Health in Everyday Life', 'health plays a crucial role in how we think, feel, and act in our daily lives. It influences how we handle stress, build relationships, and make decisions. Just like physical health, taking care of our mental well-being is essential to living a balanced and fulfilling life. When mental health is neglected, it can lead to emotional struggles, decreased productivity, and even physical health issues.\r\n\r\nIn today’s fast-paced and often stressful world, it’s important to recognize the signs of mental strain and take proactive steps to address them. This can include talking to someone you trust, practicing self-care, staying physically active, or seeking professional help. By prioritizing mental health, we not only improve our own quality of life but also create a more compassionate and understanding society.', '2025-06-20 01:10:10'),
(2, 'Perception of Stigma among Caregivers of Mentally Ill People', 'The lives of people with mental illness are altered by the symptoms of the illness, attitudes of people that view symptoms as threatening and uncomfortable, which foster stigma and discrimination within social circles. The catch of the study was to assess the stigmatizing beliefs among Caregivers of persons with mental illness. A descriptive and exploratory study was conducted with purposive sampling technique among 50 caregivers with interview method of mental illness patient with purposive sampling technique at Nepal Medical College teaching Hospital, Jorpati, Kathmandu, Nepal in Oct 2017- Nov 2017. The study revealed that the most of caregivers (62%) feel burdened financially, and about 64% think that people with mental illness are dangerous. Most of the caregivers (70%) think that the mentally ill people are violent. More than half (54%) of the caregivers think voluntary organizations are not doing adequate work in the field of mental health and 72%feel health organizations are giving less priority to mental illness as compared to other disorder. Most of the caregivers (66%) think that their occupational life has been interfered by the family member’s illness, and 56% don’t think that there are adequate law or rules regarding mental illness. In conclusion, care givers experience stigma such as concealment, negative experience, emotional stigma as well as work related stigma which affects the health and recovery of mentally ill persons. Compliance to medicine was poor and relapse rate was high which also have impact on the life of caregiver.', '2025-06-20 13:52:05'),
(3, 'Nepali Migrant Workers and Their Occupational Health Hazards in the Workplace: A Scoping Review', 'An increasing number of people are relocating to search for work, leading to substantial implications for both local and global health. Approximately 3.6% of the global population (281 million) migrates annually. Nepal has experienced a notable surge in labour migration in recent years, with a substantial proportion of its residents actively seeking work opportunities abroad. Understanding work-related risks is crucial for informing policies, interventions, and practices that can improve the welfare of this hard-to-reach population. This scoping review aims to systematically identify and analyse occupational health hazards encountered by Nepali migrant workers employed overseas. Medline, Scopus, Directory of Open Access Journals (DOAJ), and the NepJOL databases were systematically searched for primary research papers published in English up to July 2024. Relevant data, including workplace hazards and their impact on health outcomes, were extracted and narratively synthesised by highlighting key themes in the existing literature. A total of 24 articles met the inclusion criteria and were included in this review. Of these, twelve studies were conducted in Nepal, five in Gulf countries, four in Malaysia, two in Hong Kong, and one each in India and Korea. Workplace injuries (motor vehicle injuries, machinery injuries, falls from a height, and falls on a heavy object), poor working environment (including long working hours, work without leave, discrepancy in pay scale, limited access to drinking water and toilet/bathroom facilities), workplace abuse, sexual abuse, and torture were identified as key occupational health hazards faced by the Nepali migrant workers abroad. Multi-level intervention strategies, such as safety training standards, improving working conditions, and eliminating exploitative labour practices, are critical to improving occupational health and safety standards for Nepali migrant workers abroad. This includes creating a supportive working environment where employees can easily and timely access health services as needed.', '2025-06-20 13:55:00'),
(4, 'Impact of COVID-19 on poor mental health in children and young people ‘tip of the iceberg’ – UNICEF', 'NEW YORK, 5 October 2021 – Children and young people could feel the impact of COVID-19 on their mental health and well-being for many years to come, UNICEF warned in its flagship report today.\r\n\r\nAccording to The State of the World’s Children 2021; On My Mind: promoting, protecting and caring for children’s mental health – UNICEF’s most comprehensive look at the mental health of children, adolescents and caregivers in the 21st century – even before COVID-19, children and young people carried the burden of mental health conditions without significant investment in addressing them.\r\n\r\nAccording to the latest available estimates, more than 1 in 7 adolescents aged 10–19 is estimated to live with a diagnosed mental disorder globally. Almost 46,000 adolescents die from suicide each year, among the top five causes of death for their age group. Meanwhile, wide gaps persist between mental health needs and mental health funding. The report finds that about 2 per cent of government health budgets are allocated to mental health spending globally.\r\n\r\n“It has been a long, long 18 months for all of us – especially children. With nationwide lockdowns and pandemic-related movement restrictions, children have spent indelible years of their lives away from family, friends, classrooms, play – key elements of childhood itself,” said UNICEF Executive Director Henrietta Fore. “The impact is significant, and it is just the tip of the iceberg. Even before the pandemic, far too many children were burdened under the weight of unaddressed mental health issues. Too little investment is being made by governments to address these critical needs. Not enough importance is being placed on the relationship between mental health and future life outcomes.”', '2025-06-20 13:56:06');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `council_messages`
--

CREATE TABLE `council_messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `counselor_id` int(11) NOT NULL,
  `sender` enum('user','counselor') NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `council_messages`
--

INSERT INTO `council_messages` (`id`, `user_id`, `counselor_id`, `sender`, `message`, `created_at`) VALUES
(1, 1, 2, 'user', 'kij klijhg lkjhg', '2025-06-20 07:05:04'),
(2, 3, 1, 'user', 'asdfg', '2025-06-20 07:08:47'),
(3, 3, 1, 'counselor', 'why introvert??', '2025-06-20 07:09:19');

-- --------------------------------------------------------

--
-- Table structure for table `helplines`
--

CREATE TABLE `helplines` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `helplines`
--

INSERT INTO `helplines` (`id`, `name`, `phone`, `description`, `website`) VALUES
(2, 'CMC Nepal', '01-4102037', 'Center for Mental Health & Counseling-Nepal.\r\nDedicated to enhance and provide quality and affordable mental health and psychological counselling service', 'cmcnepal.org.np'),
(3, 'TPO Nepal', '014537124', 'Transcultural Psychosocial Organization Nepal\r\nPromotes psychosocial well-being and mental health of children and families in conflict affected and other vulnerable communities', 'tponepal.org'),
(4, 'KOSHISH', '015121230', 'National Mental Health Self-help Organization\r\n\"Nothing About Us Without Us\"\r\nPromotes mental health & psychological well-being in Nepal', 'info@koshish.org.np'),
(5, 'Kopila - Nepal', '61463486', 'Empowering Lives, Transforming Communities\r\nProtects human rights & psychological well-being of children, women & people with psychosocial problems', 'info@kopilanepal.org.np'),
(6, 'Mankaa Kura', '9864067676', 'WHERE YOU MATTER THE MOST\r\nHere you can have individual one to one sessions with counselors, workshops and training on mental health topics and themes', 'mankaakura.com');

-- --------------------------------------------------------

--
-- Table structure for table `inspiring_stories`
--

CREATE TABLE `inspiring_stories` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `story` text NOT NULL,
  `status` enum('pending','approved') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inspiring_stories`
--

INSERT INTO `inspiring_stories` (`id`, `user_id`, `username`, `title`, `story`, `status`, `created_at`) VALUES
(2, 1, 'ranish', 'From Panic to Purpose – A Journey Through Anxiety', 'Rina, a 22-year-old university student, was always considered the “strong one” in her family. She had excelled academically for years, but during her final year, things started to change. The pressure to graduate with honors and land a good job began to take a toll on her. She couldn’t sleep, her heart would race during exams, and she started avoiding people—fearing they might notice something was wrong.\\r\\n\\r\\nOne day during a presentation, she had her first panic attack. Her vision blurred, her breathing became shallow, and she ran out of the room sobbing. At first, she blamed it on stress and didn’t tell anyone. But when the attacks became frequent, she withdrew even more—skipping meals, isolating herself, and crying without knowing why.\\r\\n\\r\\nIt wasn’t until she visited the university\\\'s health center, almost by accident, that she was introduced to a counselor. Through regular sessions, Rina learned how anxiety works and picked up practical coping techniques—like grounding, journaling, and talking to trusted friends. She also joined a peer support group, where she met others who had similar struggles.\\r\\n\\r\\nRina\\\'s recovery wasn’t quick or perfect—some days were harder than others. But over time, she reclaimed control of her life. She passed her exams, spoke at a mental health awareness event on campus, and began mentoring younger students dealing with academic anxiety.\\r\\n\\r\\n“For the longest time, I thought I was broken. But I was just overwhelmed—and I needed help, not shame.”', 'approved', '2025-06-20 08:48:21'),
(3, 1, 'ranish', 'Bullying happens, talk to your child about it!', 'I had a hard time at school. I can’t look back at my school days without being sad. I was bullied both by my seniors and by my classmates. I used to be called names and get threats by my seniors. They used to make fun of me, call me fat and ugly. I vividly remember an incident where a group of seniors in my school called a gang to beat me up at a school program. I ran out of the building and got inside a cab for the first time in my life and rushed home. Thank god! I told my parents. They didn’t take the issue to the school authority but made sure that I felt safe. They used to make sure to drop me off at school and pick me up. I don’t even think informing the school would have made things easier, they would just call my bully’s parents and this would just increase her anger towards me. She left school the next year and I was glad. May be she doesn’t even remember the hard time I had because of her attitude towards me. But this incident has certainly changed my perception in life; it has made me stronger and less tolerable towards injustices. I didn’t know I was getting bullied at that time, I only realized it when I attended a workshop against bullying in high school,” she said with a smile on her face. Anna is now a BBA student who has friends who support and encourage her to pursue her dreams in life. ', 'approved', '2025-06-20 08:59:01'),
(4, 3, 'saman introvert', ' Devi Khadka – From Trauma to National Advocate', 'During Nepal’s civil conflict, Devi Khadka endured torture and sexual violence at just 17 under police custody. Severe trauma followed—bruised, ashamed, and struggling to even recognize her own humanity. Yet, she refused to be defined by her suffering.\\r\\n\\r\\nRather than retreating, she transformed her pain into purpose. She joined the Maoist movement, rose to platoon leader, and after the 2006 peace agreement, became a Member of Parliament. However, she regretted that survivors like herself were often ignored. Determined to change this, Devi now leads survivor-led organizations that document cases, challenge amnesty laws, and fight for rights and reparations.\\r\\n\\r\\n', 'approved', '2025-06-20 09:08:50'),
(5, 3, 'saman introvert', 'Sharmila – A Journey of Healing After Childhood Abuse', 'At only 14, Sharmila from Sindhuli endured horrific childhood abuse, even at the hands of her own father. The trauma isolated her, leaving deep emotional scars. But then Antardristi Nepal stepped in.\\r\\n\\r\\nThey provided trauma-focused counseling, shelter, and a pathway back to education. From near hopelessness, Sharmila found healing and reclaimed her future—dreaming of independence and choosing to help others in similar situations.\\r\\nHer transformation shows:\\r\\n\\r\\nHealing is possible when dignity, care, and community reach the most vulnerable ', 'approved', '2025-06-20 09:10:46');

-- --------------------------------------------------------

--
-- Table structure for table `journal_comments`
--

CREATE TABLE `journal_comments` (
  `id` int(11) NOT NULL,
  `journal_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `journal_comments`
--

INSERT INTO `journal_comments` (`id`, `journal_id`, `user_id`, `comment`, `created_at`) VALUES
(1, 5, 1, 'very nice!', '2025-06-20 16:54:57');

-- --------------------------------------------------------

--
-- Table structure for table `journal_entries`
--

CREATE TABLE `journal_entries` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `entry` text DEFAULT NULL,
  `is_private` tinyint(1) DEFAULT 1,
  `is_approved` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `journal_entries`
--

INSERT INTO `journal_entries` (`id`, `user_id`, `entry`, `is_private`, `is_approved`, `created_at`) VALUES
(5, 3, '\"I’m feeling really good today. I had a productive morning, got some things checked off my list, and even had time to enjoy a quiet cup of tea. My energy feels balanced, and mentally I feel clear. It’s one of those days where everything feels manageable, and I’m grateful for that.\"', 0, 1, '2025-06-20 16:51:20'),
(7, 1, '\"Im feeling a bit overwhelmed, to be honest. There’s so much on my plate lately, and it feels like I’m juggling too many things at once. My mind’s been racing, and it’s hard to focus on just one task. I know it’ll pass, but right now I’m just trying to breathe and stay grounded.\"', 1, 1, '2025-06-20 16:53:00');

-- --------------------------------------------------------

--
-- Table structure for table `journal_likes`
--

CREATE TABLE `journal_likes` (
  `id` int(11) NOT NULL,
  `journal_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `journal_likes`
--

INSERT INTO `journal_likes` (`id`, `journal_id`, `user_id`, `created_at`) VALUES
(11, 5, 1, '2025-06-20 16:54:12');

-- --------------------------------------------------------

--
-- Table structure for table `mood_checkin`
--

CREATE TABLE `mood_checkin` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `mood` varchar(50) DEFAULT NULL,
  `sleep_quality` varchar(50) DEFAULT NULL,
  `talk_to_friends` varchar(50) DEFAULT NULL,
  `custom_message` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mood_checkin`
--

INSERT INTO `mood_checkin` (`id`, `user_id`, `mood`, `sleep_quality`, `talk_to_friends`, `custom_message`, `created_at`) VALUES
(1, 1, 'Neutral Mood', 'Score: 4', 'Mood Quiz', '😐 You\'re doing okay but could benefit from some self-care and attention to your well-being.', '2025-06-20 09:35:26'),
(2, 1, 'Neutral Mood', 'Score: 4', 'Mood Quiz', '😐 You\'re doing okay but could benefit from some self-care and attention to your well-being.', '2025-06-20 09:40:38'),
(3, 1, 'Neutral Mood', 'Score: 4', 'Mood Quiz', '😐 You\'re doing okay but could benefit from some self-care and attention to your well-being.', '2025-06-20 09:40:53'),
(4, 1, 'Neutral Mood', 'Score: 4', 'Mood Quiz', '😐 You\'re doing okay but could benefit from some self-care and attention to your well-being.', '2025-06-20 09:43:58'),
(5, 1, 'Low Mood', 'Score: -2', 'Mood Quiz', '😟 Your mood seems low with possible signs of stress. Consider talking to someone or practicing relaxation techniques.', '2025-06-20 11:17:04'),
(6, 1, 'Positive Mood', 'Score: 10', 'Mood Quiz', '😊 You seem to be in a positive mood with good mental well-being. Keep up the great work!', '2025-06-20 11:18:23'),
(7, 3, 'Neutral Mood', 'Score: 0', 'Mood Quiz', '😐 You\'re doing okay but could benefit from some self-care and attention to your well-being.', '2025-06-20 16:40:05'),
(8, 3, 'Neutral Mood', 'Score: 4', 'Mood Quiz', '😐 You\'re doing okay but could benefit from some self-care and attention to your well-being.', '2025-06-20 16:42:09');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `is_read`, `created_at`) VALUES
(1, 1, 'VOIALITION', 'jdiaou k.nfiayusg', 1, '2025-06-20 17:31:24');

-- --------------------------------------------------------

--
-- Table structure for table `stress_resources`
--

CREATE TABLE `stress_resources` (
  `resource_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `type` enum('video','audio','article','other') NOT NULL,
  `link` varchar(512) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stress_resources`
--

INSERT INTO `stress_resources` (`resource_id`, `description`, `type`, `link`, `created_at`) VALUES
(1, 'A 15–20-minute stress-reduction meditation in Nepali, featuring simple mindfulness techniques and soothing guidance.', 'video', 'https://www.youtube.com/watch?v=LYwntnWH4hA&utm', '2025-06-20 08:15:19'),
(2, 'A focused 4-7-8 pranayama breathing guide that helps users pause, calm anxiety, and reconnect with their breath.', 'video', 'https://www.youtube.com/watch?v=Ig2Cm3_gv_4&utm', '2025-06-20 08:16:43'),
(3, 'A calm, guided breathing session in Nepali—ideal for beginners seeking stress relief and mental clarity.', 'video', 'https://www.youtube.com/watch?v=Ygrtif8e4jg&utm', '2025-06-20 08:17:35'),
(4, 'A gentle body-scan session narrated in Nepali—helps users release tension and become present in their bodies.', 'video', 'https://www.youtube.com/watch?v=HIzaI4hT2KA&utm', '2025-06-20 08:18:34');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_banned` tinyint(1) DEFAULT 0,
  `is_counciler` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `is_banned`, `is_counciler`) VALUES
(1, 'ranish', 'ranish@gmail.com', 'qwerty123', '2025-06-19 23:47:10', 0, 1),
(2, 'admin', 'admin@sambodhan.com', 'qwerty123', '2025-06-19 23:48:39', 0, 0),
(3, 'saman introvert', 'introvert@gmail.com', 'qwerty123', '2025-06-20 12:52:14', 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `council_messages`
--
ALTER TABLE `council_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `counselor_id` (`counselor_id`);

--
-- Indexes for table `helplines`
--
ALTER TABLE `helplines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inspiring_stories`
--
ALTER TABLE `inspiring_stories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `journal_comments`
--
ALTER TABLE `journal_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `journal_id` (`journal_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `journal_likes`
--
ALTER TABLE `journal_likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `journal_id` (`journal_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `mood_checkin`
--
ALTER TABLE `mood_checkin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `stress_resources`
--
ALTER TABLE `stress_resources`
  ADD PRIMARY KEY (`resource_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `council_messages`
--
ALTER TABLE `council_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `helplines`
--
ALTER TABLE `helplines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `inspiring_stories`
--
ALTER TABLE `inspiring_stories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `journal_comments`
--
ALTER TABLE `journal_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `journal_entries`
--
ALTER TABLE `journal_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `journal_likes`
--
ALTER TABLE `journal_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `mood_checkin`
--
ALTER TABLE `mood_checkin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stress_resources`
--
ALTER TABLE `stress_resources`
  MODIFY `resource_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `council_messages`
--
ALTER TABLE `council_messages`
  ADD CONSTRAINT `council_messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `council_messages_ibfk_2` FOREIGN KEY (`counselor_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `journal_comments`
--
ALTER TABLE `journal_comments`
  ADD CONSTRAINT `journal_comments_ibfk_1` FOREIGN KEY (`journal_id`) REFERENCES `journal_entries` (`id`),
  ADD CONSTRAINT `journal_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD CONSTRAINT `journal_entries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `journal_likes`
--
ALTER TABLE `journal_likes`
  ADD CONSTRAINT `journal_likes_ibfk_1` FOREIGN KEY (`journal_id`) REFERENCES `journal_entries` (`id`),
  ADD CONSTRAINT `journal_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `mood_checkin`
--
ALTER TABLE `mood_checkin`
  ADD CONSTRAINT `mood_checkin_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
