<?php
session_start();
// index.php - Sambodhan Homepage
include_once 'includes/navbar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sambodhan - Mental Health Portal</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background-image: url('assets/indexbackground2.png');
            background-color: #e0f7fa;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            color: #234;
        }
        .login-btn {
            position: absolute;
            top: 24px;
            right: 32px;
            background: #009688;
            color: #fff;
            padding: 10px 26px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.08rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            transition: background 0.2s;
            z-index: 100;
        }
        .login-btn:hover {
            background: #00796b;
        }
        .hero {
            text-align: center;
            padding: 60px 20px 40px 20px;
        }
        .hero h1 {
            font-size: 2.5rem;
            color: #00796b;
            margin-bottom: 10px;
        }
        .hero p {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 30px;
        }
        .options {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            margin: 30px 0;
        }
        .option-card {
            background: rgba(255,255,255,0.82);
            border-radius: 16px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.13);
            padding: 30px 24px;
            width: 260px;
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .option-card:hover {
            transform: translateY(-6px) scale(1.03);
            box-shadow: 0 6px 18px rgba(0,0,0,0.13);
        }
        .option-card h2 {
            color: #009688;
            font-size: 1.3rem;
            margin-bottom: 10px;
        }
        .option-card p {
            color: #555;
            font-size: 1rem;
            margin-bottom: 18px;
        }
        .option-card a {
            display: inline-block;
            background: #009688;
            color: #fff;
            padding: 8px 22px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.2s;
        }
        .option-card a:hover {
            background: #00796b;
        }
        @media (max-width: 900px) {
            .options { flex-direction: column; align-items: center; }
        }
    </style>
</head>
<body>
    <div class="hero">
        <h1>Welcome to Sambodhan</h1>
        <p>Your safe space for mental health awareness, support, and self-care.<br>
        "BREAK THE SILENCE AROUND MENTAL HEALTH."
        </p>
    </div>
    <div class="options">
        <div class="option-card">
            <h2>Mood Check-In</h2>
            <p>Reflect on your feelings with a quick, anonymous quiz.</p>
            <a href="pages/mood_checkin.php">Check Your Mood</a>
        </div>
        <div class="option-card">
            <h2>Mental Health Tips</h2>
            <p>Read helpful articles on stress relief, mindfulness, and self-care.</p>
            <a href="pages/articles.php">Read Articles</a>
        </div>
        <div class="option-card">
            <h2>Helplines</h2>
            <p>Find trusted helplines and support services in Nepal.</p>
            <a href="pages/helplines.php">Find Help</a>
        </div>
        <div class="option-card">
            <h2>Anonymous Journal</h2>
            <p>Write your thoughts privately. (Admin approval required for public posts.)</p>
            <a href="pages/journal.php">Write Journal</a>
        </div>
        <div class="option-card">
            <h2>Inspiring Stories</h2>
            <p>Read and share stories of hope, recovery, and resilience.</p>
            <a href="pages/inspiring_stories.php">View Stories</a>
        </div>
        <div class="option-card">
            <h2>For Parents & Teachers</h2>
            <p>A guide to help recognize and support children's mental health struggles.</p>
            <a href="pages/PT_guide.php">View Guide</a>
        </div>
        <div class="option-card">
            <h2>Contact Counselor</h2>
            <p>Sometimes you just need someone who listens, not judges.</p>
            <a href="pages/council_chat.php">Start Chat</a>
        </div>
        <div class="option-card">
            <h2>Stress Reduction</h2>
            <p>Explore videos, audios, and articles to help you relax and manage stress.</p>
            <a href="pages/stress_reduction.php">View Resources</a>
        </div>
    </div>
    <?php include_once 'includes/footer.php'; ?>
</body>
</html> 