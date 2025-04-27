const cells = document.querySelectorAll('.cell');

cells.forEach(cell => {
    cell.addEventListener('click', () => {
        cell.classList.toggle('filled');
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
        alert("Congratulations! You solved the Nonogram!");
    } else {
        alert("Oops, not quite right. Try again!");
    }
});
