<?php
session_start();
include_once '../includes/navbar.php';

// Define mood level function
function getMoodLevel($score) {
    if ($score >= 5) {
        return [
            'level' => 'Positive Mood',
            'message' => '😊 You seem to be in a positive mood with good mental well-being. Keep up the great work!',
            'color' => '#4CAF50',
            'emoji' => '😊',
            'suggestions' => [
                'Continue practicing self-care and maintaining healthy routines',
                'Share your positive energy with others - it can be contagious!',
                'Consider journaling about what\'s working well for you',
                'Stay connected with friends and family to maintain this positive state',
                'Use this energy to set new goals or try something you\'ve been interested in'
            ],
            'encouragement' => 'You\'re doing amazing! Your positive mindset is a strength that will help you navigate life\'s challenges. Remember to celebrate your wins, no matter how small they may seem.',
            'resources' => 'Consider exploring mindfulness practices or meditation to maintain this positive state.'
        ];
    } elseif ($score >= 0) {
        return [
            'level' => 'Neutral Mood',
            'message' => '😐 You\'re doing okay but could benefit from some self-care and attention to your well-being.',
            'color' => '#FF9800',
            'emoji' => '😐',
            'suggestions' => [
                'Try adding small moments of joy to your daily routine',
                'Practice gratitude by writing down 3 things you\'re thankful for each day',
                'Take short breaks throughout the day to stretch or breathe deeply',
                'Connect with a friend or family member for a quick chat',
                'Consider trying a new hobby or activity that interests you'
            ],
            'encouragement' => 'It\'s completely normal to feel neutral sometimes. This is actually a sign of stability, and with some gentle self-care, you can move toward feeling more positive. Every small step counts!',
            'resources' => 'Light exercise, spending time in nature, or listening to uplifting music can help boost your mood.'
        ];
    } elseif ($score >= -4) {
        return [
            'level' => 'Low Mood',
            'message' => '😟 Your mood seems low with possible signs of stress. Consider talking to someone or practicing relaxation techniques.',
            'color' => '#FF5722',
            'emoji' => '😟',
            'suggestions' => [
                'Reach out to a trusted friend, family member, or counselor',
                'Practice deep breathing exercises for 5-10 minutes daily',
                'Try to maintain a regular sleep schedule and get adequate rest',
                'Engage in light physical activity like walking or gentle stretching',
                'Limit screen time and social media if it\'s affecting your mood'
            ],
            'encouragement' => 'It\'s okay to not be okay. Many people experience low moods, and it doesn\'t mean anything is wrong with you. You\'re showing strength by recognizing how you feel. Remember, this is temporary and you have the power to feel better.',
            'resources' => 'Consider talking to a mental health professional, joining a support group, or using mental health apps for guided meditation and mood tracking.'
        ];
    } else {
        return [
            'level' => 'Negative Mood',
            'message' => '😞 You may be struggling. Please consider reaching out to a trusted person, family member, or mental health professional for support.',
            'color' => '#F44336',
            'emoji' => '😞',
            'suggestions' => [
                'Please reach out to a mental health professional as soon as possible',
                'Talk to a trusted friend, family member, or counselor about how you\'re feeling',
                'Consider calling a mental health helpline for immediate support',
                'Try to maintain basic self-care: eat regular meals, get some sleep, stay hydrated',
                'Avoid isolating yourself - even small social interactions can help'
            ],
            'encouragement' => 'You are not alone, and your feelings are valid. It takes courage to recognize when you need help, and reaching out is a sign of strength, not weakness. There are people who care about you and want to help you feel better.',
            'resources' => 'If you\'re in crisis, please call a mental health crisis line immediately. Professional help can make a significant difference in how you feel.',
            'crisis_info' => 'If you\'re having thoughts of self-harm, please call a crisis helpline or go to the nearest emergency room. You matter and help is available.'
        ];
    }
}

// Handle quiz submission
$moodResult = null;
$score = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answers = [];
    
    // Calculate score from form answers
    for ($i = 1; $i <= 10; $i++) {
        $key = 'q' . $i;
        if (isset($_POST[$key])) {
            $answer = intval($_POST[$key]);
            $score += $answer;
            $answers[$i] = $answer;
        }
    }
    
    // Only process if we have answers
    if (count($answers) > 0) {
        $moodResult = getMoodLevel($score);
        
        // Save to database if user is logged in
        if (isset($_SESSION['user_id'])) {
            $conn = new mysqli('localhost', 'root', '', 'sambodhan');
            if (!$conn->connect_error) {
                $user_id = intval($_SESSION['user_id']);
                $stmt = $conn->prepare("INSERT INTO mood_checkin (user_id, mood, sleep_quality, talk_to_friends, custom_message) VALUES (?, ?, ?, ?, ?)");
                $mood_text = $moodResult['level'];
                $sleep_quality = "Score: " . $score;
                $talk_to_friends = "Mood Quiz";
                $custom_message = $moodResult['message'];
                $stmt->bind_param('issss', $user_id, $mood_text, $sleep_quality, $talk_to_friends, $custom_message);
                $stmt->execute();
                $stmt->close();
                $conn->close();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mental Health & Mood Quiz | Sambodhan</title>
    <style>
        body {
            background: #e0f7fa;
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #009688, #00796b);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 2.2rem;
            font-weight: 600;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 1.1rem;
            opacity: 0.9;
        }
        .content {
            padding: 40px;
        }
        .quiz-form {
            display: none;
        }
        .result-section {
            display: none;
            text-align: center;
        }
        .mood-emoji {
            font-size: 4rem;
            margin-bottom: 20px;
        }
        .score-display {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 20px 0;
            color: #009688;
        }
        .mood-level {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .mood-message {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #555;
            margin-bottom: 30px;
        }
        .question {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            border-left: 4px solid #009688;
        }
        .question h3 {
            margin: 0 0 15px 0;
            color: #333;
            font-size: 1.1rem;
        }
        .options {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .option {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .option:hover {
            border-color: #009688;
            background: #f0f9f8;
        }
        .option input[type="radio"] {
            margin-right: 12px;
            transform: scale(1.2);
        }
        .option.selected {
            border-color: #009688;
            background: #e0f7fa;
        }
        .btn {
            background: #009688;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            margin: 10px;
        }
        .btn:hover {
            background: #00796b;
        }
        .btn-secondary {
            background: #757575;
        }
        .btn-secondary:hover {
            background: #616161;
        }
        .progress-bar {
            background: #e0e0e0;
            border-radius: 10px;
            height: 8px;
            margin-bottom: 30px;
            overflow: hidden;
        }
        .progress-fill {
            background: #009688;
            height: 100%;
            width: 0%;
            transition: width 0.3s;
        }
        .instructions {
            background: #e8f5e8;
            border: 1px solid #c8e6c9;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            color: #2e7d32;
        }
        .instructions h3 {
            margin: 0 0 10px 0;
            color: #2e7d32;
        }
        .instructions p {
            margin: 0;
            line-height: 1.5;
        }
        .encouragement-section,
        .suggestions-section,
        .resources-section,
        .crisis-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            margin: 20px 0;
            text-align: left;
            border-left: 4px solid #009688;
        }
        .encouragement-section {
            background: #e8f5e8;
            border-left-color: #4CAF50;
        }
        .suggestions-section {
            background: #fff3e0;
            border-left-color: #FF9800;
        }
        .resources-section {
            background: #e3f2fd;
            border-left-color: #2196F3;
        }
        .crisis-section {
            background: #ffebee;
            border-left-color: #F44336;
        }
        .encouragement-section h3,
        .suggestions-section h3,
        .resources-section h3,
        .crisis-section h3 {
            margin: 0 0 15px 0;
            color: #333;
            font-size: 1.2rem;
            font-weight: 600;
        }
        .encouragement-section p,
        .resources-section p,
        .crisis-section p {
            margin: 0;
            line-height: 1.6;
            color: #555;
        }
        .suggestions-section ul {
            margin: 0;
            padding-left: 20px;
        }
        .suggestions-section li {
            margin-bottom: 10px;
            line-height: 1.5;
            color: #555;
        }
        .action-buttons {
            margin-top: 30px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }
        .btn-help {
            background: #2196F3;
        }
        .btn-help:hover {
            background: #1976D2;
        }
        @media (max-width: 600px) {
            .content {
                padding: 20px;
            }
            .header {
                padding: 20px;
            }
            .header h1 {
                font-size: 1.8rem;
            }
            .question {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🧠 Mental Health & Mood Quiz</h1>
            <p>Take a moment to check in with yourself</p>
        </div>
        
        <div class="content">
            <?php if (!isset($moodResult)): ?>
                <!-- Quiz Form -->
                <div class="quiz-form" id="quizForm">
                    <div class="instructions">
                        <h3>📋 Instructions</h3>
                        <p>Choose the option that best describes how you've been feeling lately. Be honest with yourself - this helps you understand your current mental state.</p>
                    </div>
                    
                    <div class="progress-bar">
                        <div class="progress-fill" id="progressFill"></div>
                    </div>
                    
                    <form method="POST" id="moodQuiz">
                        <div class="question">
                            <h3>1. How do you feel when you wake up in the morning?</h3>
                            <div class="options">
                                <label class="option">
                                    <input type="radio" name="q1" value="1" required>
                                    <span>😊 Refreshed and ready for the day</span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="q1" value="0">
                                    <span>😐 Neither good nor bad</span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="q1" value="-1">
                                    <span>😞 Tired or unmotivated</span>
                                </label>
                            </div>
                        </div>

                        <div class="question">
                            <h3>2. How has your appetite been recently?</h3>
                            <div class="options">
                                <label class="option">
                                    <input type="radio" name="q2" value="1" required>
                                    <span>🍽️ Normal or good appetite</span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="q2" value="0">
                                    <span>🤷‍♂️ Not much change</span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="q2" value="-1">
                                    <span>🍞 Lost or no interest in food</span>
                                </label>
                            </div>
                        </div>

                        <div class="question">
                            <h3>3. How often do you enjoy activities you used to like?</h3>
                            <div class="options">
                                <label class="option">
                                    <input type="radio" name="q3" value="1" required>
                                    <span>🎉 Often or always</span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="q3" value="0">
                                    <span>😶 Occasionally</span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="q3" value="-1">
                                    <span>😔 Rarely or never</span>
                                </label>
                            </div>
                        </div>

                        <div class="question">
                            <h3>4. How do you feel about yourself most of the time?</h3>
                            <div class="options">
                                <label class="option">
                                    <input type="radio" name="q4" value="1" required>
                                    <span>💪 Confident and positive</span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="q4" value="0">
                                    <span>😶 Just okay</span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="q4" value="-1">
                                    <span>😟 Critical or disappointed</span>
                                </label>
                            </div>
                        </div>

                        <div class="question">
                            <h3>5. How well do you sleep?</h3>
                            <div class="options">
                                <label class="option">
                                    <input type="radio" name="q5" value="1" required>
                                    <span>😴 Sleep well and feel rested</span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="q5" value="0">
                                    <span>😐 Some trouble falling asleep</span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="q5" value="-1">
                                    <span>🛌 Trouble staying asleep or not rested</span>
                                </label>
                            </div>
                        </div>

                        <div class="question">
                            <h3>6. How do you handle stress these days?</h3>
                            <div class="options">
                                <label class="option">
                                    <input type="radio" name="q6" value="1" required>
                                    <span>💆 Calm and in control</span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="q6" value="0">
                                    <span>😐 Can manage, but it's hard</span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="q6" value="-1">
                                    <span>😰 Overwhelmed and anxious</span>
                                </label>
                            </div>
                        </div>

                        <div class="question">
                            <h3>7. How connected do you feel to friends or family?</h3>
                            <div class="options">
                                <label class="option">
                                    <input type="radio" name="q7" value="1" required>
                                    <span>🤝 Very connected and supported</span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="q7" value="0">
                                    <span>👥 Somewhat connected</span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="q7" value="-1">
                                    <span>🙁 Isolated or alone</span>
                                </label>
                            </div>
                        </div>

                        <div class="question">
                            <h3>8. How often do you feel hopeful about the future?</h3>
                            <div class="options">
                                <label class="option">
                                    <input type="radio" name="q8" value="1" required>
                                    <span>🌞 Very often</span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="q8" value="0">
                                    <span>🤔 Sometimes</span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="q8" value="-1">
                                    <span>🌧️ Rarely or never</span>
                                </label>
                            </div>
                        </div>

                        <div class="question">
                            <h3>9. How often do you laugh or smile during the day?</h3>
                            <div class="options">
                                <label class="option">
                                    <input type="radio" name="q9" value="1" required>
                                    <span>😂 Frequently</span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="q9" value="0">
                                    <span>🙂 Occasionally</span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="q9" value="-1">
                                    <span>😐 Almost never</span>
                                </label>
                            </div>
                        </div>

                        <div class="question">
                            <h3>10. How do you feel about your daily routine?</h3>
                            <div class="options">
                                <label class="option">
                                    <input type="radio" name="q10" value="1" required>
                                    <span>🚀 Engaging and productive</span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="q10" value="0">
                                    <span>😶 Repetitive but manageable</span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="q10" value="-1">
                                    <span>💤 Boring or meaningless</span>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn">Submit Quiz</button>
                    </form>
            </div>
        <?php else: ?>
                <!-- Results Section -->
                <div class="result-section">
                    <div class="mood-emoji"><?php echo $moodResult['emoji']; ?></div>
                    <div class="score-display">Score: <?php echo $score; ?></div>
                    <div class="mood-level" style="color: <?php echo $moodResult['color']; ?>">
                        <?php echo $moodResult['level']; ?>
                    </div>
                    <div class="mood-message">
                        <?php echo $moodResult['message']; ?>
                    </div>
                    
                    <!-- Encouragement Section -->
                    <div class="encouragement-section">
                        <h3>💝 Words of Encouragement</h3>
                        <p><?php echo $moodResult['encouragement']; ?></p>
                    </div>
                    
                    <!-- Suggestions Section -->
                    <div class="suggestions-section">
                        <h3>💡 Suggestions to Help You</h3>
                        <ul>
                            <?php foreach ($moodResult['suggestions'] as $suggestion): ?>
                                <li><?php echo $suggestion; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    
                    <!-- Resources Section -->
                    <div class="resources-section">
                        <h3>🔗 Additional Resources</h3>
                        <p><?php echo $moodResult['resources']; ?></p>
                    </div>
                    
                    <?php if (isset($moodResult['crisis_info'])): ?>
                        <!-- Crisis Information -->
                        <div class="crisis-section">
                            <h3>🚨 Important Information</h3>
                            <p><?php echo $moodResult['crisis_info']; ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <a href="mood_checkin.php" class="btn">Take Quiz Again</a>
                        <a href="../index.php" class="btn btn-secondary">Back to Home</a>
                        <a href="helplines.php" class="btn btn-help">Get Help & Support</a>
                    </div>
                </div>
        <?php endif; ?>
        </div>
    </div>

    <script>
        // Show appropriate sections based on quiz state
        document.addEventListener('DOMContentLoaded', function() {
            const quizForm = document.getElementById('quizForm');
            const resultSection = document.querySelector('.result-section');
            
            // Show quiz form if no results, show results if available
            if (quizForm) {
                quizForm.style.display = 'block';
            }
            if (resultSection) {
                resultSection.style.display = 'block';
            }
            
            // Add visual feedback for selected options
            const options = document.querySelectorAll('.option');
            options.forEach(option => {
                const radio = option.querySelector('input[type="radio"]');
                radio.addEventListener('change', function() {
                    // Remove selected class from siblings
                    const parent = this.closest('.options');
                    parent.querySelectorAll('.option').forEach(opt => {
                        opt.classList.remove('selected');
                    });
                    // Add selected class to current option
                    this.closest('.option').classList.add('selected');
                    
                    // Update progress bar
                    updateProgress();
                });
            });
        });
        
        function updateProgress() {
            const questions = document.querySelectorAll('.question');
            const answered = document.querySelectorAll('input[type="radio"]:checked').length;
            const progress = (answered / questions.length) * 100;
            
            const progressFill = document.getElementById('progressFill');
            if (progressFill) {
                progressFill.style.width = progress + '%';
            }
        }
        
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