<?php
$puzzle = [
    [1, 0, 1, 1, 0],
    [1, 1, 0, 1, 1],
    [0, 1, 1, 1, 0],
    [1, 1, 0, 1, 1],
    [0, 1, 1, 1, 0]
];

// Fungsi generate hints
function getHints($puzzle, $direction = 'row') {
    $hints = [];
    if ($direction == 'row') {
        foreach ($puzzle as $row) {
            $hint = [];
            $count = 0;
            foreach ($row as $cell) {
                if ($cell == 1) {
                    $count++;
                } else {
                    if ($count > 0) {
                        $hint[] = $count;
                        $count = 0;
                    }
                }
            }
            if ($count > 0) {
                $hint[] = $count;
            }
            if (empty($hint)) {
                $hint[] = 0;
            }
            $hints[] = $hint;
        }
    } else if ($direction == 'col') {
        $cols = count($puzzle[0]);
        for ($i = 0; $i < $cols; $i++) {
            $hint = [];
            $count = 0;
            foreach ($puzzle as $row) {
                if ($row[$i] == 1) {
                    $count++;
                } else {
                    if ($count > 0) {
                        $hint[] = $count;
                        $count = 0;
                    }
                }
            }
            if ($count > 0) {
                $hint[] = $count;
            }
            if (empty($hint)) {
                $hint[] = 0;
            }
            $hints[] = $hint;
        }
    }
    return $hints;
}

$rowHints = getHints($puzzle, 'row');
$colHints = getHints($puzzle, 'col');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nonogram Game</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f0f0;
            margin: 0;
            padding: 20px;
            text-align: center;
        }

        h1 {
            margin-bottom: 10px;
        }

        #timer {
            font-size: 18px;
            margin-bottom: 20px;
        }

        #nonogram {
            display: grid;
            grid-template-columns: 50px repeat(<?= count($puzzle[0]) ?>, 40px);
            grid-template-rows: 50px repeat(<?= count($puzzle) ?>, 40px);
            gap: 2px;
            justify-content: center;
            margin: 0 auto;
        }

        .cell, .hint {
            width: 40px;
            height: 40px;
            background-color: #fff;
            border: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            user-select: none;
        }

        .hint {
            background-color: #f9f9f9;
            font-weight: bold;
            font-size: 12px;
            padding: 2px;
        }

        .cell {
            cursor: pointer;
            transition: background-color 0.2s, transform 0.2s;
        }

        .cell.filled {
            background-color: #333;
        }

        .cell.marked {
            background-color: #eee;
            color: red;
            font-weight: bold;
        }

        #checkButton {
            margin-top: 20px;
            padding: 10px 25px;
            font-size: 16px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        #checkButton:hover {
            background-color: #218838;
        }

        #winMessage {
            display: none;
            margin-top: 20px;
            font-size: 22px;
            color: #28a745;
            animation: pop 0.6s ease forwards;
        }

        @keyframes pop {
            0% { transform: scale(0.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body>
    <h1>Nonogram</h1>
    <div id="timer">⏱️ 0s</div>

    <div id="nonogram">
        <!-- Sudut kiri atas kosong -->
        <div class="hint"></div>

        <!-- Petunjuk atas -->
        <?php foreach ($colHints as $hint): ?>
            <div class="hint"><?= implode('<br>', $hint) ?></div>
        <?php endforeach; ?>

        <!-- Petunjuk kiri + grid -->
        <?php foreach ($rowHints as $i => $hintRow): ?>
            <div class="hint"><?= implode(' ', $hintRow) ?></div>
            <?php for ($j = 0; $j < count($puzzle[$i]); $j++): ?>
                <div class="cell" data-row="<?= $i ?>" data-col="<?= $j ?>"></div>
            <?php endfor; ?>
        <?php endforeach; ?>
    </div>

    <button id="checkButton">Check Puzzle</button>
    <div id="winMessage">🎉 You Win! 🎉</div>

    <script>
        const solution = <?php echo json_encode($puzzle); ?>;
        const cells = document.querySelectorAll('.cell');
        const timerElement = document.getElementById('timer');
        const winMessage = document.getElementById('winMessage');

        let seconds = 0;
        const timer = setInterval(() => {
            seconds++;
            timerElement.textContent = `⏱️ ${seconds}s`;
        }, 1000);

        cells.forEach(cell => {
            cell.addEventListener('click', (e) => {
                if (cell.classList.contains('marked')) {
                    cell.classList.remove('marked');
                }
                cell.classList.toggle('filled');
            });

            cell.addEventListener('contextmenu', (e) => {
                e.preventDefault();
                if (cell.classList.contains('filled')) {
                    cell.classList.remove('filled');
                }
                cell.classList.toggle('marked');
            });
        });

        document.getElementById('checkButton').addEventListener('click', () => {
            let correct = true;

            cells.forEach(cell => {
                const row = cell.dataset.row;
                const col = cell.dataset.col;
                const isFilled = cell.classList.contains('filled');
                const shouldBeFilled = solution[row][col] === 1;

                if (isFilled !== shouldBeFilled) {
                    correct = false;
                }
            });

            if (correct) {
                clearInterval(timer);
                winMessage.style.display = 'block';
            } else {
                alert("❌ Not correct yet! Keep trying!");
            }
        });
    </script>
</body>
</html>
