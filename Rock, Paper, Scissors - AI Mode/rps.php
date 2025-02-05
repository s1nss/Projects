<?php

session_start();
if (!isset($_SESSION['wins'])) {
    $_SESSION['wins'] = 0;        
    $_SESSION['losses'] = 0;
    $_SESSION['ties'] = 0;
}

$choices = ["Rock", "Paper", "Scissors"];

$playerChoice = isset($_GET['choice']) && in_array($_GET['choice'], $choices) ? $_GET['choice'] : null;

if (!isset($SESSION['last_player_choice'])) {
    $_SESSION['last_player_choice'] = $choices[array_rand($choices)];
}

$aiCounterMoves = [
    "Rock" => ["Paper", "Scissors"],
    "Paper" => ["Scissors", "Rock"],
    "Scissors" => ["Rock", "Paper"]
];

$computerChoice = $aiCounterMoves[$_SESSION['last_player_choice']][array_rand($aiCounterMoves[$_SESSION['last_player_choice']])];


function determineWinner($player, $computer) {
    if ($player === $computer) {
        $_SESSION['ties']++;
        return "It's a tie! 🤝";
    }
    if (
        ($player === "Rock" && $computer === "Scissors") ||
        ($player === "Paper" && $computer === "Rock") ||
        ($player === "Scissors" && $computer === "Paper")    
    ) {
        $_SESSION ['wins']++;
        return "You win! 🎉";
    } else {
        $_SESSION ['losses']++;
        return "AI win! 🤖";
    }
}

$result = "";
if($playerChoice) {
    $SESSION['last_player_choice'] = $playerChoice;
    $result = determineWinner($playerChoice, $computerChoice);
}

if (isset($_GET['reset'])) {
    session_unset();
    session_destroy();
    header("Location: rps.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rock Paper Scissors - AI Mode</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .choices { margin: 20px; }
        button { padding: 10px 20px; font-size: 18px; margin: 5px; cursor: pointer; }
        .scoreboard { font-size: 20px; margin-top: 20px; }
        .reset { background-color: red; color: white; }
    </style>
</head>
<body>
    <h1>Rock, Paper, Scissors - AI Mode!</h1>

    <form method="GET">
        <div class="choices">
            <button type="submit" name="choice" value="Rock">🪨 Rock</button>
            <button type="submit" name="choice" value="Paper">📜 Paper</button>
            <button type="submit" name="choice" value="Scissors">✂️ Scissors</button>
        </div>
    </form>

    <?php if ($playerChoice): ?>
        <h2>You chose: <?= htmlspecialchars($playerChoice) ?></h2>
        <h2>AI chose: <?= htmlspecialchars($computerChoice) ?></h2>
        <h2><strong><?= $result ?></strong></h2>
    <?php endif; ?>

    <div class="scoreboard">
        <h2>Scoreboard</h2>
        <p>🏆 Wins: <?= $_SESSION['wins'] ?> | 🤖 Losses: <?= $_SESSION['losses'] ?> | 🤝 Ties: <?= $_SESSION['ties'] ?></p>
        <form method="GET">
            <button type="submit" name="reset" class="reset">Reset Game</button>
        </form>
    </div>
</body>
</html>
