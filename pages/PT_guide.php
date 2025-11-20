<?php
session_start();
include_once '../includes/navbar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Their Silent Cries: A Guide for Parents & Teachers | Sambodhan</title>
    <style>
        body {
            background: #f5f8ff;
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 600;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 1.2rem;
            opacity: 0.9;
            font-style: italic;
        }
        .content {
            padding: 40px;
        }
        .section {
            margin-bottom: 40px;
            padding: 30px;
            background: #f8f9fa;
            border-radius: 12px;
            border-left: 4px solid #3498db;
        }
        .section h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }
        .section h3 {
            color: #34495e;
            margin: 25px 0 15px 0;
            font-size: 1.4rem;
        }
        .highlight-box {
            background: #e8f4fd;
            border: 1px solid #b3d9ff;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .warning-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .success-box {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .quote {
            background: #f1f2f6;
            border-left: 4px solid #3498db;
            padding: 20px;
            margin: 20px 0;
            font-style: italic;
            font-size: 1.1rem;
        }
        .toolkit-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .toolkit-table th,
        .toolkit-table td {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: left;
        }
        .toolkit-table th {
            background: #34495e;
            color: white;
            font-weight: 600;
        }
        .toolkit-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        .red-flags {
            background: #ffe6e6;
            border: 1px solid #ffcccc;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .red-flags h4 {
            color: #c0392b;
            margin-top: 0;
        }
        .red-flags ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .red-flags li {
            margin-bottom: 8px;
            color: #2c3e50;
        }
        .student-quotes {
            background: #f0f8ff;
            border: 1px solid #b3d9ff;
            border-radius: 8px;
            padding: 25px;
            margin: 20px 0;
        }
        .student-quotes h4 {
            color: #2980b9;
            margin-top: 0;
        }
        .quote-item {
            background: white;
            border-radius: 6px;
            padding: 15px;
            margin: 15px 0;
            border-left: 3px solid #3498db;
        }
        .better-alternatives {
            background: #e8f5e8;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .better-alternatives h4 {
            color: #27ae60;
            margin-top: 0;
        }
        .better-alternatives ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .better-alternatives li {
            margin-bottom: 8px;
            color: #2c3e50;
        }
        .exercise-box {
            background: #fff8e1;
            border: 1px solid #ffecb3;
            border-radius: 8px;
            padding: 25px;
            margin: 20px 0;
        }
        .exercise-box h4 {
            color: #f57c00;
            margin-top: 0;
        }
        .cta-section {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 40px;
            text-align: center;
            border-radius: 12px;
            margin: 30px 0;
        }
        .cta-section h3 {
            margin: 0 0 15px 0;
            font-size: 1.8rem;
        }
        .cta-section p {
            margin: 0 0 25px 0;
            font-size: 1.1rem;
            opacity: 0.9;
        }
        .btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            display: inline-block;
            margin: 10px;
        }
        .btn:hover {
            background: #c0392b;
        }
        .btn-secondary {
            background: #95a5a6;
        }
        .btn-secondary:hover {
            background: #7f8c8d;
        }
        .banner-quote {
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 12px;
            margin: 30px 0;
            font-size: 1.3rem;
            font-weight: 500;
        }
        @media (max-width: 768px) {
            .content {
                padding: 20px;
            }
            .header {
                padding: 30px 20px;
            }
            .header h1 {
                font-size: 2rem;
            }
            .section {
                padding: 20px;
            }
            .toolkit-table {
                font-size: 0.9rem;
            }
            .toolkit-table th,
            .toolkit-table td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Their Silent Cries: A Guide for Parents & Teachers</h1>
            <p>"Because not all pain is loud. And not all silence is peace."</p>
        </div>
        
        <div class="content">
            <div class="banner-quote">
                "Children may not always speak, but they're always listening."
            </div>

            <div class="section">
                <h2>The Invisible Load: What Our Children Are Carrying</h2>
                
                <div class="highlight-box">
                    <h4>Did You Know?</h4>
                    <p>Nearly 1 in 3 adolescents report feeling emotionally overwhelmed due to study pressure, comparison, and lack of emotional validation.</p>
                </div>

                <h3>What this looks like:</h3>
                <div class="quote">
                    <p>"My son is quiet, but maybe he's okay..."</p>
                </div>
                
                <div class="quote">
                    <p>"She's on her phone all day — must be lazy."</p>
                    <p>Could be anxiety, fear of failure, or self-esteem issues.</p>
                </div>

                <div class="success-box">
                    <h4>What to do instead:</h4>
                    <ul>
                        <li>Ask: "What's going on in your heart?"</li>
                        <li>Avoid: "Comparison brings motivation" (this usually causes shame)</li>
                        <li>Create space for honest conversations</li>
                        <li>Validate their feelings without judgment</li>
                    </ul>
                </div>
            </div>

            <div class="section">
                <h2>What Does a Child in Distress Look Like?</h2>
                
                <h3>Emotionally struggling students may:</h3>
                <ul>
                    <li>Start scoring poorly despite studying</li>
                    <li>Become irritable or easily angry</li>
                    <li>Use phrases like "I wish I could disappear"</li>
                    <li>Avoid school, friends, or family dinners</li>
                    <li>Get physically sick without medical cause</li>
                    <li>Show changes in eating or sleeping patterns</li>
                    <li>Express feelings of hopelessness or worthlessness</li>
                </ul>

                <div class="red-flags">
                    <h4>Red Flags Teachers Should Never Ignore:</h4>
                    <ul>
                        <li>Sudden classroom withdrawal</li>
                        <li>Overly self-deprecating language</li>
                        <li>Signs of bullying (quiet crying, broken belongings)</li>
                        <li>Changes in eating or sleeping patterns</li>
                        <li>Expressing hopelessness or worthlessness</li>
                        <li>Giving away prized possessions</li>
                        <li>Sudden improvement in mood after being depressed</li>
                    </ul>
                </div>
            </div>

            <div class="section">
                <h2>What a Student Wishes Their Parent/Teacher Would Say</h2>
                
                <div class="student-quotes">
                    <h4>Real student quotes (gathered from forums & reports):</h4>
                    <div class="quote-item">
                        "I wish my parents asked me how I feel, not just how I did in exams."
                    </div>
                    <div class="quote-item">
                        "My teacher said 'I'm here if you want to talk.' That one line saved me."
                    </div>
                    <div class="quote-item">
                        "I wanted to cry, but they said 'be a man.'"
                    </div>
                    <div class="quote-item">
                        "I'm scared to tell my mom I'm tired. She says I'm weak."
                    </div>
                    <div class="quote-item">
                        "I feel like I'm disappointing everyone all the time."
                    </div>
                </div>

                <div class="better-alternatives">
                    <h4>Better alternatives:</h4>
                    <ul>
                        <li>"Your health matters more than your marks."</li>
                        <li>"I'm proud of how hard you tried."</li>
                        <li>"If you're feeling heavy, I'll listen — no judging."</li>
                        <li>"It's okay to not be okay sometimes."</li>
                        <li>"I believe in you, even when you don't believe in yourself."</li>
                        <li>"You don't have to be perfect to be loved."</li>
                        <li>"I'm here for you, no matter what."</li>
                    </ul>
                </div>
            </div>

            <div class="section">
                <h2>Your Role Isn't to Fix — It's to Be There</h2>
                
                <h3>What helps the most?</h3>
                <ul>
                    <li>Listening without interrupting</li>
                    <li>Naming emotions gently: "You seem sad today?"</li>
                    <li>Creating a safe space at home/school</li>
                    <li>Validating feelings without trying to solve everything</li>
                    <li>Being consistent and reliable</li>
                    <li>Showing unconditional love and acceptance</li>
                    <li>Modeling healthy emotional expression</li>
                </ul>

                <div class="exercise-box">
                    <h4>Try this exercise:</h4>
                    <p>Every evening, ask:</p>
                    <div class="quote">
                        "Today, what made you smile? What made you feel sad or stressed?"
                    </div>
                    <p>Let it become a habit. Small moments build deep trust.</p>
                </div>
            </div>


            <div class="cta-section">
                <h3>Start the conversation. Even if it's uncomfortable.</h3>
                <p>You might just save a life.</p>
                <a href="mood_checkin.php" class="btn">Take Mental Health Quiz</a>
                <a href="helplines.php" class="btn btn-secondary">Get Professional Help</a>
            </div>

        </div>
    </div>

    <script>
        // Update notification count
        function updateNotificationCount() {
            fetch('/Sambodhan/pages/get_notification_count.php')
                .then(response => response.json())
                .then(data => {
                    const countElement = document.getElementById('notification-count');
                    if (countElement) {
                        if (data.count > 0) {
                            countElement.textContent = data.count;
                            countElement.style.display = 'flex';
                        } else {
                            countElement.style.display = 'none';
                        }
                    }
                })
                .catch(error => console.error('Error fetching notification count:', error));
        }
        
        // Update count on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateNotificationCount();
        });
    </script>
    
    <?php include_once '../includes/footer.php'; ?>
</body>
</html> 