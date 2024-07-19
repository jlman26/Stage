<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 centered-select">
            <h3>Create quiz title and description</h3>
            <input type="text" id="title" name="title" class="submit-control"/>
            <input type="text" id="description" name="description" class="submit-control"/>
            
            
            <h3>Create questions</h3>
            <input type="number" id="questions" name="questions" min="1" max="20" class="form-control" />
            <button id="submit" class="btn btn-primary mt-2" onclick="submitQuestions()">Enter</button>
            <div id="questionContainer" class="mt-4"></div>
            
        </div>
    </div>
    <div id="result" class="mt-4"></div>
</div>