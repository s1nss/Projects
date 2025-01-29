<?php
session_start();

// Reset history if requested
if (isset($_POST['reset'])) {
    $_SESSION['history'] = [];
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Funny Magic 8-Ball Responses
$responses = [
    "Yes, but only if you bring me coffee ☕.",
    "Nope, not today. Ask again when I'm in a good mood 😴.",
    "Absolutely! But you owe me a pizza 🍕.",
    "Maybe... but do you really want to know the answer? 🤔",
    "Sure, why not? 🤷‍♂️",
    "Ask again after you do 10 push-ups 💪.",
    "100% yes! The PHP gods have spoken 📜.",
    "No way! Not in this universe 🚀.",
    "Only if you say 'PHP is the best' three times 🙃.",
    "Yes, but don't tell anyone I told you... it's top secret 🤫."
];

// Function to get a random response
function getMagic8BallResponse() {
    global $responses;
    return $responses[array_rand($responses)];
}

// Store session history
if (!isset($_SESSION['history'])) {
    $_SESSION['history'] = [];
}

$question = isset($_POST['question']) ? trim($_POST['question']) : "";
$answer = "";

if (!empty($question)) {
    $answer = getMagic8BallResponse();
    $_SESSION['history'][] = ["question" => $question, "answer" => $answer];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magic 8-Ball 🎱</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #111;
            color: white;
            margin: 50px;
        }
        h1 {
            color: #4CAF50;
        }
        .container {
            background: #222;
            padding: 20px;
            border-radius: 10px;
            width: 50%;
            margin: auto;
            box-shadow: 0 0 15px rgba(0, 255, 0, 0.5);
        }
        input, button {
            padding: 10px;
            font-size: 16px;
            margin-top: 10px;
        }
        input {
            width: 80%;
            border: 2px solid #4CAF50;
            background: #333;
            color: white;
            border-radius: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .magic-ball {
            width: 120px;
            height: 120px;
            background: radial-gradient(circle, #000, #222);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 30px;
            color: white;
            margin: 20px auto;
            border: 5px solid white;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
            transition: transform 0.5s, box-shadow 0.5s;
        }
        .glow {
            box-shadow: 0 0 30px rgba(0, 255, 255, 0.8);
        }
        .shake {
            animation: shake 0.5s ease-in-out;
        }
        @keyframes shake {
            0% { transform: rotate(0deg); }
            20% { transform: rotate(10deg); }
            40% { transform: rotate(-10deg); }
            60% { transform: rotate(10deg); }
            80% { transform: rotate(-10deg); }
            100% { transform: rotate(0deg); }
        }
        .response {
            margin-top: 20px;
            font-size: 20px;
            color: #FFD700;
        }
        .history {
            margin-top: 20px;
            text-align: left;
            background: #444;
            padding: 10px;
            border-radius: 10px;
        }
        .history h2 {
            color: #FFD700;
        }
        .reset-btn {
            background-color: #ff4444;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <h1>Magic 8-Ball 🎱</h1>
    <div class="container">
        <p>Ask the mighty 8-Ball a question, and receive wisdom (or nonsense)!</p>

        <div class="magic-ball" id="ball">🎱</div>

        <form method="POST" onsubmit="shakeBall();">
            <input type="text" name="question" placeholder="Type your question here..." required>
            <br>
            <button type="submit">Ask the 8-Ball!</button>
        </form>

        <?php if (!empty($question)): ?>
            <div class="response">
                <p>🎱 You asked: <strong><?php echo htmlspecialchars($question); ?></strong></p>
                <p>🤖 Magic 8-Ball says: <strong id="answer"><?php echo $answer; ?></strong></p>
            </div>
            <script>
                setTimeout(() => {
                    glowBall();
                    speakAnswer("<?php echo addslashes($answer); ?>");
                }, 500);
            </script>
        <?php endif; ?>

        <form method="POST">
            <button type="submit" name="reset" class="reset-btn">Reset History</button>
        </form>

        <div class="history">
            <h2>Past Questions:</h2>
            <ul>
                <?php foreach ($_SESSION['history'] as $item): ?>
                    <li><strong><?php echo htmlspecialchars($item['question']); ?></strong> - <?php echo $item['answer']; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <script>
        function shakeBall() {
            let ball = document.getElementById("ball");
            ball.classList.add("shake");
            setTimeout(() => {
                ball.classList.remove("shake");
            }, 500);
        }

        function glowBall() {
            let ball = document.getElementById("ball");
            ball.classList.add("glow");
            setTimeout(() => {
                ball.classList.remove("glow");
            }, 1000);
        }

        function speakAnswer(text) {
            let speech = new SpeechSynthesisUtterance();
            speech.text = text;
            speech.lang = 'en-US';
            speech.volume = 1;
            speech.rate = 1;
            speech.pitch = 1;
            window.speechSynthesis.speak(speech);
        }
    </script>

</body>
</html>
