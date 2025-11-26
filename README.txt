# Khmer_Quizter
Admin Email: Lonol@gmail.com
Admin Password: Tengchantola123

CREATE TABLE Useraccount(
    UserID int(11) AI PK,
    Username varchar(100), 
    Email varchar(150), 
    Password varchar(255), 
    Role enum('Admin','Guest','User'), 
    Status enum('enabled','disabled'), 
    Profile varchar(255), 
    CreatedAt timestamp, 
    CreateData timestamp
);

CREATE TABLE Quiz(
    QuizID int(11) AI PK,
    CreatorID int(11),
    QuizTitle varchar(255), 
    QuizCode varchar(50), 
    Image varchar(255), 
    Type varchar(100), 
    Play int(11), 
    CreatedAt timestamp, 
    CreateData timestamp,
);

CREATE TABLE Question(
    QuestionID int(11) AI PK ,
    Question text ,
    Duration int(11), 
    CreateDate datetime
);

CREATE TABLE Anwers(
    AnswerID int(11) AI PK,
    QuestionID int(11), 
    Answer text, 
    is_correct tinyint(1), 
    CreateDate datetime
);

CREATE TABLE Score(
    ScoreID int(11) AI PK,
    UserID int(11), 
    QuizID int(11), 
    ScoreValue int(11), 
    Date timestamp
);

CREATE TABLE Quizquestion(
    QuestionID int(11) AI PK,
    QuizID int(11), 
    QuestionText text, 
    OptionA varchar(255), 
    OptionB varchar(255), 
    OptionC varchar(255), 
    OptionD varchar(255), 
    CorrectAnswer enum('A','B','C','D'), 
    CreatedAt timestamp,
);

CREATE TABLE Userquizattempts(
    AttemptID int(11) AI PK,
    UserID int(11), 
    QuizID int(11), 
    Score int(11), 
    CompletedAt datetime
);