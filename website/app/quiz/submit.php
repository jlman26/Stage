<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title']) && isset($_POST['description']) && isset($_POST['questions'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $questions = json_decode($_POST['questions'], true);

    $servername = "mysql";  // Replace with your server name
    $username = "root";     // Replace with your MySQL username
    $password = "root";     // Replace with your MySQL password
    $dbname = "Quiz";       // Replace with your database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die(json_encode(array('error' => 'Connection failed: ' . $conn->connect_error)));
    }

    // Insert quiz details
    $quizSql = "INSERT INTO quizzes (title, description) VALUES (?, ?)";
    $quizStmt = $conn->prepare($quizSql);
    $quizStmt->bind_param("ss", $title, $description);

    if (!$quizStmt->execute()) {
        die(json_encode(array('error' => 'Failed to insert quiz details: ' . $conn->error)));
    }

    // Get the auto-generated quiz_id
    $quizId = $conn->insert_id;

    // Prepare statements for questions and answers
    $questionSql = "INSERT INTO questions (quiz_id, question) VALUES (?, ?)";
    $answerSql = "INSERT INTO answers (question_id, answer, correct) VALUES (?, ?, ?)";

    $questionStmt = $conn->prepare($questionSql);
    $answerStmt = $conn->prepare($answerSql);

    if (!$questionStmt || !$answerStmt) {
        die(json_encode(array('error' => 'Failed to prepare SQL statements: ' . $conn->error)));
    }

    // Insert each question with its answers
    foreach ($questions as $question) {
        $questionText = $question['questionText'];

        $questionStmt->bind_param("is", $quizId, $questionText);
        if (!$questionStmt->execute()) {
            die(json_encode(array('error' => 'Failed to execute question statement: ' . $questionStmt->error)));
        }

        $questionId = $conn->insert_id;

        foreach ($question['answers'] as $answerText) {
            $correctAnswer = ($answerText === $question['correctAnswer']) ? 1 : 0;

            $answerStmt->bind_param("iss", $questionId, $answerText, $correctAnswer);
            if (!$answerStmt->execute()) {
                die(json_encode(array('error' => 'Failed to execute answer statement: ' . $answerStmt->error)));
            }
        }
    }

    // Close statements and connection
    $quizStmt->close();
    $questionStmt->close();
    $answerStmt->close();
    $conn->close();

    echo json_encode(array('message' => 'Quiz created successfully', 'quizId' => $quizId));
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
}
?>
