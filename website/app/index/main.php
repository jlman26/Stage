<?php include 'db.php'; ?>

<div class="container flex-grow-1">
    <h2 class="text-center text-light mb-4">Quizzes to Play</h2>
    <div class="row justify-content-center">
        <?php
        // Fetch quizzes from the database
        $sql = "SELECT id, title, description FROM quizzes";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data for each quiz
            while($row = $result->fetch_assoc()) {
                echo '<div class="col-md-4 mb-4">';
                echo '<div class="card-body card border-0 bg-custom">';
                echo '<h5 class="card-title text-light">' . htmlspecialchars($row["title"]) . '</h5>';
                echo '<p class="card-text text-light">' . htmlspecialchars($row["description"]) . '</p>';
                echo '<a href="quiz.php?id=' . htmlspecialchars($row["id"]) . '" class="btn background-button">Start Quiz</a>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p class="text-light">No quizzes available.</p>';
        }

        $conn->close();
        ?>
    </div>
    <p class="card-text text-light">Add your own quiz!</p>
    <a href="addQuiz.php" class="btn background-button">Add Quiz</a>
</div>
