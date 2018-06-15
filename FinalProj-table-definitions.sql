SET FOREIGN_KEY_CHECKS = 0;

-- Create articles table
DROP TABLE IF EXISTS `articles`;
CREATE TABLE articles (
    doi VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    pubdate DATE NOT NULL,
    jid INT NOT NULL,
    PRIMARY KEY (doi),
    FOREIGN KEY (jid)
        REFERENCES journals(id)
        ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Create journals table
DROP TABLE IF EXISTS `journals`;
CREATE TABLE journals (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    publisher VARCHAR(255) NOT NULL,
    abbr VARCHAR(255) NOT NULL,
    impfact DOUBLE,
    PRIMARY KEY (id),
    UNIQUE KEY (name, publisher)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Create author table
DROP TABLE IF EXISTS `author`;
CREATE TABLE author (
    id INT NOT NULL AUTO_INCREMENT,
    fname VARCHAR(255) NOT NULL,
    mname VARCHAR(255),
    lname VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Create institutions table
DROP TABLE IF EXISTS `institutions`;
CREATE TABLE institutions (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    city VARCHAR(255) NOT NULL,
    state VARCHAR(255),
    country VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Create funders table
DROP TABLE IF EXISTS `funders`;
CREATE TABLE funders (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(255),
    country VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Create keywords table
DROP TABLE IF EXISTS `keywords`;
CREATE TABLE keywords (
    kwrd VARCHAR(255) NOT NULL,
    PRIMARY KEY (kwrd)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Create authors_articles table
DROP TABLE IF EXISTS `authors_articles`;
CREATE TABLE authors_articles (
    artid VARCHAR(255) NOT NULL,
    authid INT NOT NULL,
    ord_of_app INT NOT NULL,
    PRIMARY KEY (artid, authid),
    UNIQUE KEY (artid, authid, ord_of_app),
    FOREIGN KEY (artid)
        REFERENCES articles(doi)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (authid)
        REFERENCES author(id)
        ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Create authors_inst table
DROP TABLE IF EXISTS `authors_inst`;
CREATE TABLE authors_inst (
    authid INT,
    instid INT,
    currinst TINYINT,
    startdate DATE NOT NULL,
    enddate DATE,
    PRIMARY KEY (authid, instid, startdate),
    FOREIGN KEY (authid)
        REFERENCES author(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (instid)
        REFERENCES institutions(id)
        ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Create articles_funders table
DROP TABLE IF EXISTS `articles_funders`;
CREATE TABLE articles_funders (
    artid VARCHAR(255),
    fundid INT,
    PRIMARY KEY (artid, fundid),
    FOREIGN KEY (artid)
        REFERENCES articles(doi)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (fundid)
        REFERENCES funders(id)
        ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Create citations table
DROP TABLE IF EXISTS `citations`;
CREATE TABLE citations (
    citingid VARCHAR(255),
    citedid VARCHAR(255),
    PRIMARY KEY (citingid, citedid),
    FOREIGN KEY (citingid)
        REFERENCES articles(doi)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (citedid)
        REFERENCES articles(doi)
        ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Create articles_keywords table
DROP TABLE IF EXISTS `articles_keywords`;
CREATE TABLE articles_keywords (
    artid VARCHAR(255) NOT NULL,
    kwrd VARCHAR(255) NOT NULL,
    PRIMARY KEY (artid, kwrd),
    FOREIGN KEY (artid)
        REFERENCES articles(doi)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (kwrd)
        REFERENCES keywords(kwrd)
        ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Put some date into the tables
INSERT INTO journals (name, publisher, abbr, impFact) VALUES
("Computer Programming Monthly", "Institute of Computer Science", "Comp Program Mon", 1.574),
("Biosciences Journal", "Harris Publishing", "Biosci J", 4.882),
("Chemical Interactions", "Chemical Society", "Chem Interact", 3.429),
("Journal of Physics", "Physicists of America", "J Phys", 5.727),
("Data Science Updates", "Lewison-Hubbard", "Data Sci Updates", 1.284);

INSERT INTO articles (doi, title, pubdate, jid) VALUES
("1234A", "The Most Efficient Search Algorithms", "2012-03-21", 1),
("2345B", "The Differences Between Objects and Structures", "2005-09-22", 1),
("3456C", "Cell Nuclei Structures and Functions", "1998-11-02", 2),
("4567D", "Evolutionary Origins of Zebra Stripes", "2015-06-05", 2),
("5678E", "Hexane Properties and Reactions", "2010-01-03", 3),
("6789F", "Rate Equations for Hydroxyl Mediated Reactions", "1975-08-17", 3),
("7890G", "Quantum Fluctuations in the Aether Caused by Gravity Waves", "2016-02-20", 4),
("8901H", "Maxwell\'s Equations and the Conservation of Angular Momentum", "2003-10-22", 4),
("9012I", "Merging and Validating Customer Data from Multiple Databases", "2015-05-31", 5),
("0123J", "Identifying Trends in SAT and GRE Data from Massive Longtitudinal Studies", "2016-01-12", 5);

INSERT INTO articles_funders (artid, fundid) VALUES
("1234A", 1),
("2345B", 2), ("2345B", 3),
("3456C", 4), ("3456C", 5), ("3456C", 1),
("4567D", 2), ("4567D", 3),
("5678E", 4),
("6789F", 5), ("6789F", 1),
("7890G", 2), ("7890G", 3), ("7890G", 4),
("8901H", 5), ("8901H", 1), ("8901H", 2),
("9012I", 2),
("0123J", 3), ("0123J", 4);

INSERT INTO authors_articles (artid, authid, ord_of_app) VALUES
("1234A", 1, 1),
("2345B", 2, 1), ("2345B", 3, 2),
("3456C", 4, 1), ("3456C", 5, 2), ("3456C", 6, 3),
("4567D", 7, 1), ("4567D", 8, 2), ("4567D", 9, 3), ("4567D", 10, 4),
("5678E", 1, 1), ("5678E", 2, 2), ("5678E", 3, 3), ("5678E", 4, 4), ("5678E", 5, 5),
("6789F", 6, 1), ("6789F", 7, 2), ("6789F", 8, 3), ("6789F", 9, 4),
("7890G", 10, 1), ("7890G", 1, 2), ("7890G", 2, 3),
("8901H", 3, 1), ("8901H", 4, 2), 
("9012I", 5, 1),
("0123J", 6, 1), ("0123J", 7, 2);

INSERT INTO citations (citingid, citedid) VALUES
("1234A", "2345B"), ("1234A", "3456C"), ("1234A", "4567D"), ("1234A", "5678E"), ("1234A", "6789F"),
("2345B", "3456C"), ("2345B", "4567D"), ("2345B", "5678E"), ("2345B", "6789F"), ("2345B", "7890G"),
("3456C", "4567D"), ("3456C", "5678E"), ("3456C", "6789F"), ("3456C", "7890G"), ("3456C", "8901H"),
("4567D", "5678E"), ("4567D", "6789F"), ("4567D", "7890G"), ("4567D", "8901H"), ("4567D", "9012I"),
("5678E", "6789F"), ("5678E", "7890G"), ("5678E", "8901H"), ("5678E", "9012I"), ("5678E", "0123J"),
("6789F", "7890G"), ("6789F", "8901H"), ("6789F", "9012I"), ("6789F", "0123J"), ("6789F", "1234A"),
("7890G", "8901H"), ("7890G", "9012I"), ("7890G", "0123J"), ("7890G", "1234A"), ("7890G", "2345B"),
("8901H", "9012I"), ("8901H", "0123J"), ("8901H", "1234A"), ("8901H", "2345B"), ("8901H", "3456C"),
("9012I", "0123J"), ("9012I", "1234A"), ("9012I", "2345B"), ("9012I", "3456C"), ("9012I", "4567D"),
("0123J", "1234A"), ("0123J", "2345B"), ("0123J", "3456C"), ("0123J", "4567D"), ("0123J", "5678E");

INSERT INTO keywords (kwrd) VALUES
("alcohol functional group"), 
("bubble sort"),
("carbon chemistry"),
("chemistry"),
("computer programming"),
("conservation laws"),
("correlation"),
("covalent bonding"),
("data security"),
("databases"),
("dna"),
("electromagnetism"),
("equids"),
("inheritance"),
("isomers"),
("joins"),
("linear fitting"),
("object-oriented programming"),
("osmosis"),
("Poynting vector"),
("protein synthesis"),
("quick sort"),
("uncertainty principle"),
("wavefunctions"),
("zoology");

INSERT INTO articles_keywords (kwrd, artid) VALUES
("bubble sort", "1234A"), ("computer programming", "1234A"), ("quick sort", "1234A"),
("computer programming", "2345B"), ("object-oriented programming", "2345B"), ("inheritance", "2345B"),
("osmosis", "3456C"), ("protein synthesis", "3456C"), ("dna", "3456C"),
("dna", "4567D"), ("zoology", "4567D"), ("equids", "4567D"),
("chemistry", "5678E"), ("carbon chemistry", "5678E"), ("isomers", "5678E"),
("alcohol functional group", "6789F"), ("chemistry", "6789F"), ("covalent bonding", "6789F"),
("wavefunctions", "7890G"), ("conservation laws", "7890G"), ("uncertainty principle", "7890G"),
("electromagnetism", "8901H"), ("Poynting vector", "8901H"), ("conservation laws", "8901H"),
("databases", "9012I"), ("joins", "9012I"), ("data security", "9012I"),
("correlation", "0123J"), ("databases", "0123J"), ("linear fitting", "0123J");

INSERT INTO author (fname, mname, lname) VALUES
("Jesse", "Anthony", "Biddle"),
("Alyssa", "Stephanie", "Naeher"),
("Arquimedes", "Ryan", "Caminero"),
("Rebecca", "Crystal", "Sauerbrunn"),
("Gerrit", "Jameson", "Cole"),
("Alison", "Crystal", "Krieger"),
("Neftali", NULL, "Feliz"),
("Kelly", "Alexis", "O\'Hara"),
("Tyler", "Jonathan", "Glasnow"),
("Ashlyn", "Mallory", "Harris");

INSERT INTO institutions (name, city, state, country) VALUES
("Harvard University", "Cambridge", "Massachusetts", "United States"),
("Sandia National Laboratories", "Albuquerque", "New Mexico", "United States"),
("Max Planck Institute for Terrestrial Microbiology", "Marburg", NULL, "Germany"),
("University of Pittsburgh", "Pittsburgh", "Pennsylvania", "United States"),
("Beijing Normal University", "Beijing", NULL, "China");

INSERT INTO authors_inst (authid, instid, currinst, startdate, enddate) VALUES
(1, 5, 0, "1974-01-13", "2005-02-14"), (1, 4, 1, "2005-02-15", NULL),
(2, 4, 0, "1974-03-16", "2006-04-17"), (2, 3, 1, "2006-04-18", NULL),
(3, 3, 0, "1974-05-19", "2007-06-20"), (3, 2, 1, "2007-06-21", NULL),
(4, 2, 0, "1974-07-22", "2008-08-23"), (4, 1, 1, "2008-08-24", NULL),
(5, 1, 0, "1974-09-25", "2009-10-26"), (5, 5, 1, "2009-10-27", NULL),
(6, 5, 0, "1974-11-28", "2010-12-29"), (6, 4, 1, "2010-12-30", NULL),
(7, 4, 0, "1974-01-31", "2011-02-01"), (7, 3, 1, "2011-02-02", NULL),
(8, 3, 0, "1974-03-03", "2012-04-04"), (8, 2, 1, "2012-04-05", NULL),
(9, 2, 0, "1974-05-06", "2013-06-07"), (9, 1, 1, "2013-06-08", NULL),
(10, 1, 0, "1974-07-09", "2014-08-10"), (10, 5, 1, "2014-08-11", NULL);

INSERT INTO funders (name, type, country) VALUES
("Department of Energy", "Government", "United States"),
("Wellcome Trust", "Charity", "United Kingdom"),
("National Natural Science Foundation", "Government", "China"),
("National Institutes of Health", "Government", "United States"),
("European Research Council", "Government", "European Union");


SET FOREIGN_KEY_CHECKS = 1;