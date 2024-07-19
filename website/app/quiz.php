<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/styles2.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
</head>
<body class="d-flex flex-column min-vh-100 gradient-custom">

<nav class="navbar navbar-expand-lg navbar-dark bg-custom">
    <a class="returnmain" href="index.php">↩ Return to Mainpage</a>
    <div class="container d-flex flex-column align-items-center">
        <div class="d-flex align-items-center mt-2"></div>
    </div>
</nav>

<div id="content-container">
    <div id="big-window">
        <div id="quiz-info" class="font-weight-bold">
            <?php
            // Database connection parameters
            $servername = "mysql";  // Replace with your server name
            $username = "root";     // Replace with your MySQL username
            $password = "root";     // Replace with your MySQL password
            $dbname = "Quiz";       // Replace with your database name

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Get quiz ID from URL
            $quiz_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

            // Fetch quiz information
            $quiz_sql = "SELECT title, description FROM quizzes WHERE id = ?";
            $stmt = $conn->prepare($quiz_sql);
            $stmt->bind_param("i", $quiz_id);
            $stmt->execute();
            $stmt->bind_result($title, $description);
            $stmt->fetch();
            $stmt->close();

            echo "<h1>" . htmlspecialchars($title) . "</h1>";
            echo "<p>" . htmlspecialchars($description) . "</p>";

            // Fetch quiz questions and answers
            $questions_sql = "
                SELECT q.id AS question_id, q.question, a.id AS answer_id, a.answer
                FROM questions q
                JOIN answers a ON q.id = a.question_id
                WHERE q.quiz_id = ?
                ORDER BY q.id, a.id
            ";
            $stmt = $conn->prepare($questions_sql);
            $stmt->bind_param("i", $quiz_id);
            $stmt->execute();
            $result = $stmt->get_result();

            $questions = [];
            while ($row = $result->fetch_assoc()) {
                $questions[$row['question_id']]['question'] = $row['question'];
                $questions[$row['question_id']]['answers'][] = [
                    'answer_id' => $row['answer_id'],
                    'answer' => $row['answer']
                ];
            }

            $stmt->close();
            $conn->close();
            ?>
        </div>

        <form id="quiz-form" method="POST" action="submitQuiz.php">
            <?php foreach ($questions as $question_id => $question_data): ?>
                <div class="form-group">
                    <h4><?php echo htmlspecialchars($question_data['question']); ?></h4>
                    <?php foreach ($question_data['answers'] as $answer): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="question_<?php echo $question_id; ?>" id="answer_<?php echo $answer['answer_id']; ?>" value="<?php echo $answer['answer_id']; ?>">
                            <label class="form-check-label" for="answer_<?php echo $answer['answer_id']; ?>">
                                <?php echo htmlspecialchars($answer['answer']); ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
            <button type="submit" class="form-group btn btn-primary">Submit Quiz</button>
        </form>
    </div>
</div>


</body>
</html>
