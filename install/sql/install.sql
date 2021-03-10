SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS Users;
CREATE TABLE Users
(
    UserID SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    Username VARCHAR(50) NOT NULL UNIQUE,
    Password VARCHAR(80) NOT NULL,
    FirstName VARCHAR(40) NULL,
    LastName VARCHAR(40) NULL,
    Session VARCHAR(64) NULL,

    PRIMARY KEY (UserID),
    UNIQUE (Username)
);

DROP TABLE IF EXISTS PostStatuses;
CREATE TABLE PostStatuses
(
    StatusID TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    StatusName VARCHAR(20) NOT NULL,

    PRIMARY KEY (StatusID)
);

DROP TABLE IF EXISTS Posts;
CREATE TABLE Posts
(
    PostID INT UNSIGNED NOT NULL AUTO_INCREMENT,
    AuthorID SMALLINT UNSIGNED NOT NULL,
    StatusID TINYINT UNSIGNED NOT NULL,
    PublishedDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    Title VARCHAR(100) NOT NULL,
    Summary VARCHAR(200) NULL,
    Body TEXT NULL,

    PRIMARY KEY (PostID),
    FOREIGN KEY (AuthorID) REFERENCES Users(UserID),
    FOREIGN KEY (StatusID) REFERENCES PostStatuses(StatusID)
);

DROP TABLE IF EXISTS PostTags;
CREATE TABLE PostTags
(
    TagID INT UNSIGNED NOT NULL AUTO_INCREMENT,
    TagName VARCHAR(100) NOT NULL,

    PRIMARY KEY (TagID),
    UNIQUE (TagName)
);

DROP TABLE IF EXISTS PostTagLookup;
CREATE TABLE PostTagLookup
(
    PostID INT UNSIGNED NOT NULL,
    TagID INT UNSIGNED NOT NULL,

    CONSTRAINT PK_PostTagLookup PRIMARY KEY (PostID, TagID)
);
SET FOREIGN_KEY_CHECKS=1;

INSERT INTO PostStatuses(StatusName)
VALUES ('Published'), ('Draft'), ('Deleted');
