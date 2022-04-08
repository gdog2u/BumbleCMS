SET FOREIGN_KEY_CHECKS=0;

/* Base / Misc table*/
DROP TABLE IF EXISTS BumbleOptions;
CREATE TABLE BumbleOptions
(
    OptionID INT NOT NULL AUTO_INCREMENT,
    OptionName VARCHAR(100) NOT NULL,
    OptionNamePrinted VARCHAR(255) NOT NULL,
    OptionValue VARCHAR(500) NOT NULL,
    LastUpdated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),

    PRIMARY KEY (OptionID)
);

DROP INDEX Username ON Users;
DROP TABLE IF EXISTS Users;
CREATE TABLE Users
(
    UserID INT NOT NULL AUTO_INCREMENT,
    Username VARCHAR(50) NOT NULL UNIQUE,
    Password VARCHAR(80) NOT NULL,
    FirstName VARCHAR(40) NULL,
    LastName VARCHAR(40) NULL,
    Session VARCHAR(64) NULL,

    PRIMARY KEY (UserID)
);

DROP TABLE IF EXISTS Statuses;
CREATE TABLE Statuses
(
    StatusID TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    StatusName VARCHAR(20) NOT NULL,

    PRIMARY KEY (StatusID)
);

/* Tables pertaining to Posts */
DROP TABLE IF EXISTS Posts;
CREATE TABLE Posts
(
    PostID INT UNSIGNED NOT NULL AUTO_INCREMENT,
    PostCategoryID INT NOT NULL,
    AuthorID INT NOT NULL,
    StatusID TINYINT UNSIGNED NOT NULL,
    Slug VARCHAR(100) NOT NULL,
    Title VARCHAR(100) NOT NULL,
    Summary VARCHAR(200) NULL,
    Body TEXT NULL,
    PublishedDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),

    PRIMARY KEY (PostID),
    FOREIGN KEY (AuthorID) REFERENCES Users(UserID),
    FOREIGN KEY (StatusID) REFERENCES Statuses(StatusID)
);

DROP TABLE IF EXISTS PostCategories;
CREATE TABLE PostCategories
(
    PostCategoryID INT NOT NULL AUTO_INCREMENT,
    CategoryName VARCHAR(100) NOT NULL,
    CategoryURIPrefix VARCHAR(125) NOT NULL,
    TemplateFile VARCHAR(255) NOT NULL,

    PRIMARY KEY (PostCategoryID)
);

ALTER TABLE Posts
ADD CONSTRAINT FK_Posts_PostCategoryID FOREIGN KEY(PostCategoryID) REFERENCES PostCategories(PostCategoryID);

DROP INDEX TagName ON Tags;
DROP TABLE IF EXISTS Tags;
CREATE TABLE Tags
(
    TagID INT UNSIGNED NOT NULL AUTO_INCREMENT,
    TagName VARCHAR(100) NOT NULL UNIQUE,

    PRIMARY KEY (TagID)
);

DROP TABLE IF EXISTS PostTagLookup;
CREATE TABLE PostTagLookup
(
    PostID INT UNSIGNED NOT NULL,
    TagID INT UNSIGNED NOT NULL,

    CONSTRAINT PK_PostTagLookup PRIMARY KEY (PostID, TagID)
);
SET FOREIGN_KEY_CHECKS=1;

/* Media Tables */

/* Add some default rows to tables */
INSERT INTO Statuses(StatusName)
VALUES ('Published'), ('Draft'), ('Deleted');

INSERT INTO BumbleOptions(OptionName, OptionNamePrinted, OptionValue, LastUpdated)
VALUES ('site-title', 'Site Title', 'The Hive', CURRENT_TIMESTAMP),
    ('active-theme', 'Active Theme', 'Bumble CMS', CURRENT_TIMESTAMP),
    ('active-theme-path', 'Active Theme File Path', 'BumbleCMS', CURRENT_TIMESTAMP)
