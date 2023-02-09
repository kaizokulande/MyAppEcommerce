-- Active: 1673506242885@@127.0.0.1@3306@kaibaidb

CREATE USER 'sakuradb'@'localhost' IDENTIFIED BY 'NOkaizoku13010407';
GRANT ALL PRIVILEGES ON SDB.* TO 'sakuradb'@'localhost' WITH GRANT OPTION;
CREATE DATABASE kaibaidb;
USE kaibaidb;

CREATE TABLE users(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(45),
    lastname VARCHAR(45),
    birthday DATE,
    gender VARCHAR(10),
    email TEXT,
	email_verified_at TIMESTAMP,
	remember_token TEXT,
    password TEXT,
	logo_type VARCHAR(30),
    sign_date TIMESTAMP NULL,
    delete_date TIMESTAMP NULL,
    spec VARCHAR(7),
	last_seen TIMESTAMP NULL,
	status INT
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE users ADD COLUMN status INT;
ALTER TABLE users MODIFY logo_type VARCHAR(30);

CREATE TABLE confirm_users(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_user INT UNSIGNED NOT NULL,
	code TEXT,
	FOREIGN KEY (id_user) REFERENCES users(id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE fr_categories(
	id_categorie INT UNSIGNED NOT NULL PRIMARY KEY,
	categorie VARCHAR(50)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;
/* shop */ /* shop picture -> logo */
CREATE TABLE shops(
	id INT UNSIGNED NOT NULL,
    id_shop INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    shop_name TEXT NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    shop_email VARCHAR(50) DEFAULT NULL,
    creation_date TIMESTAMP NOT NULL,
    delete_date TIMESTAMP NULL,
    descriptions TEXT,
    shop_site TEXT,
	shop_logo TEXT,
	shop_picture TEXT,
	FOREIGN KEY (id) REFERENCES users(id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE articles(
	id_article INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	id INT UNSIGNED NOT NULL,
	id_creator_shop INT UNSIGNED NOT NULL,
	id_shop INT UNSIGNED,
    id_categorie INT UNSIGNED NOT NULL,
    article_name VARCHAR(20) NOT NULL,
    color VARCHAR(20),
	sizes VARCHAR(30),
	quantity INT,
    price FLOAT NOT NULL,
    creation_date TIMESTAMP NOT NULL,
    delete_date TIMESTAMP NULL,
    descriptions TEXT,
	discount INT,
	total_price FLOAT,
	large_images TEXT,
    small_images TEXT,
	states VARCHAR(8),
    FOREIGN KEY (id_categorie) REFERENCES fr_categories(id_categorie),
    FOREIGN KEY (id) REFERENCES users(id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE articles MODIFY total_price FLOAT;
/* alter table articles add id_creator_shop INT UNSIGNED NOT NULL after id; */

CREATE OR REPLACE VIEW articles_categories AS SELECT articles.*,fr_categories.categorie FROM articles,fr_categories WHERE articles.id_categorie=fr_categories.id_categorie;





/* user_shop: admins */
CREATE TABLE user_shop(
	id INT UNSIGNED NOT NULL,
	id_shop INT UNSIGNED NOT NULL,
	states VARCHAR(8),
	FOREIGN KEY (id) REFERENCES users(id),
	FOREIGN KEY (id_shop) REFERENCES shops(id_shop)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO user_shop VALUES('1','1','admin');
INSERT INTO user_shop VALUES('2','1','blocked');

/* shop users */
CREATE OR REPLACE VIEW admin_shop AS SELECT user_shop.id,shops.id as id_creator,shops.id_shop,shops.shop_name,shops.phone_number,shops.shop_email,shops.creation_date,shops.delete_date,shops.descriptions,shops.shop_site,shops.shop_logo,shops.shop_picture FROM shops,user_shop WHERE shops.id_shop=user_shop.id_shop AND shops.id != user_shop.id AND user_shop.states!='blocked';

CREATE OR REPLACE VIEW user_admin_shop AS SELECT shops.* FROM shops UNION SELECT admin_shop.* FROM admin_shop;

/* get admins */

CREATE OR REPLACE VIEW user_admins AS SELECT users.id,users.firstname,users.lastname,users.gender,users.email,users.logo_type,user_admin_shop.id_shop,users.last_seen FROM users,user_admin_shop WHERE users.id=user_admin_shop.id;

/* insert users shop */

/* DELIMITER //
CREATE PROCEDURE insert_user_shop(id INT, id_shop INT)
BEGIN
	INSERT INTO user_shop VALUES(id,id_shop);
END//
DELIMITER ; */

/* /insert users shop */

/* article_shop categories */

CREATE TABLE old_articles(
	id_article INT UNSIGNED NOT NULL,
	id INT UNSIGNED NOT NULL,
	id_shop INT UNSIGNED NOT NULL,
    id_categorie INT UNSIGNED NOT NULL,
    article_name VARCHAR(20) NOT NULL,
    color VARCHAR(20),
	sizes VARCHAR(30),
	quantity INT,
    price FLOAT NOT NULL,
    creation_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    delete_date TIMESTAMP NULL,
    descriptions TEXT,
	discount INT,
	post_charge FLOAT,
	total_price INT,
	large_images TEXT,
    small_images TEXT,
	states VARCHAR(8),
    FOREIGN KEY (id) REFERENCES users(id),
    FOREIGN KEY (id_categorie) REFERENCES categories(id_categorie),
	FOREIGN KEY (id_shop) REFERENCES shops(id_shop)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE shop_oldinfo(
    id INT UNSIGNED NOT NULL ,
    id_shop INT UNSIGNED NOT NULL,
    shop_name TEXT NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    shop_email VARCHAR(50) DEFAULT NULL,
    change_date TIMESTAMP,
    delete_date TIMESTAMP NULL,
    descriptions TEXT,
    shop_site TEXT,
	shop_logo TEXT,
	shop_picture TEXT,
    FOREIGN KEY (id) REFERENCES users(id),
    FOREIGN KEY (id_shop) REFERENCES shops(id_shop)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE solded_articles(
	id_shop INT UNSIGNED NOT NULL,
	id INT UNSIGNED NOT NULL,
	id_article INT NOT NULL,
	solded_dates TIMESTAMP,
	quantity INT,
	total_price INT,
	states VARCHAR(8),
	FOREIGN KEY (id_shop) REFERENCES shops(id_shop),
	FOREIGN KEY (id) REFERENCES users(id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE commercials(
	id_cm INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_shop INT UNSIGNED NOT NULL,
	dates TIMESTAMP,
	title VARCHAR(50),
	big_image TEXT,
	FOREIGN KEY (id_shop) REFERENCES shops(id_shop)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE old_commercials(
	id_cm INT UNSIGNED NOT NULL,
	id_shop INT UNSIGNED NOT NULL,
	change_date TIMESTAMP,
	title VARCHAR(50),
	descriptions TEXT,
	big_image TEXT,
	small_image TEXT,
	action VARCHAR(8),
	FOREIGN KEY (id_shop) REFERENCES shops(id_shop)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


/* shop */

/* users */
CREATE TABLE user_solded_articles(
	id INT UNSIGNED NOT NULL,
	id_article INT NOT NULL,
	solded_dates TIMESTAMP,
	delete_date TIMESTAMP NULL,
	quantity INT,
	total_price INT,
	states VARCHAR(8),
	FOREIGN KEY (id) REFERENCES users(id),
	FOREIGN KEY (id_article) REFERENCES articles(id_article)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE purchase(
	id INT UNSIGNED NOT NULL,
	id_article INT NOT NULL,
	purchase_dates TIMESTAMP,
	delete_date TIMESTAMP NULL,
	quantity INT,
	total_price INT,
	states VARCHAR(9),
	FOREIGN KEY (id) REFERENCES users(id),
	FOREIGN KEY (id_article) REFERENCES articles(id_article)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


DELIMITER //
CREATE FUNCTION calctotal(price INT, quantity INT)
RETURNS INT
BEGIN
	DECLARE total_price INT;
	SET total_price = 0;
	IF quantity >= 0 THEN
		SET total_price = price * quantity;
	END IF;
	RETURN total_price;
END;
// DELIMITER;

	INSERT INTO fr_categories VALUES(001,'Fashion');
	INSERT INTO fr_categories VALUES(003,'Bébé');
	INSERT INTO fr_categories VALUES(004,'Sport');
	INSERT INTO fr_categories VALUES(005,'Telephone,Camera,TV');
	INSERT INTO fr_categories VALUES(006,'PC');
	INSERT INTO fr_categories VALUES(007,'Chaussures et Bottes');
	INSERT INTO fr_categories VALUES(008,'Meuble');
	INSERT INTO fr_categories VALUES(009,'Livre');
	INSERT INTO fr_categories VALUES(010,'Jeux Video');
	INSERT INTO fr_categories VALUES(011,'Beauté');
	INSERT INTO fr_categories VALUES(012,'Parfum');
	INSERT INTO fr_categories VALUES(013,'Cuisine');
 
/*---------------------OK -------------------*/
/* ALTER TABLE user_solded_articles ADD delete_date TIMESTAMP AFTER solded_dates; */

/* CREATE TABLE bought(
    id INT UNSIGNED NOT NULL,
    id_article INT NOT NULL,
    quantity INT,
    bought_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id) REFERENCES users(id),
    FOREIGN KEY (id_article) REFERENCES articles(id_article)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE color(
    color_name VARCHAR(20)
)ENGINE=InnoDB DEFAULT CHARSET=utf8; */

/* notifications */
CREATE TABLE notifications(
    id_notif INT NOT NULL PRIMARY KEY,
    notif_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    notif TEXT NOT NULL,
    states VARCHAR(10)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;
/* /notifications */

/* when delete account */
CREATE TABLE account_memory(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(45),
    lastname VARCHAR(45),
    birthday DATE,
    email VARCHAR(50),
    password TEXT,
    sign_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    delete_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    spec VARCHAR(7)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;
/* /when delete account */

delete from client_articles where id=1;
¥

SELECT * FROM articles WHERE id_article IN (10,11);


SELECT table_schema "kaibaidb", round(sum(data_length+index_length)/1024/1024,2) as taille_en_mega FROM information_schema.TABLES GROUP BY table_schema;

SELECT * FROM articles WHERE creation_date=(SELECT MAX(creation_date)) GROUP BY id_article; 

drop table articles;
drop table shop;
drop table users;

drop table articles_memory;
drop table shop_memory;
drop table account_memory;
SET autocommit=0;
SELECT * FROM users WHERE email='landernaud@gmail.com' AND u_password=(SELECT SHA1('12345678'));

/* Stock users */
CREATE OR REPLACE VIEW articles_user AS SELECT articles.*,fr_categories.categorie FROM articles,fr_categories WHERE articles.id_categorie=fr_categories.id_categorie AND articles.id_shop=0;

/* /users sum solded articles */
CREATE OR REPLACE VIEW sums_user AS SELECT MAX(solded_dates) AS dates,id_article, SUM(quantity) AS quantity,SUM(total_price) AS total_price FROM user_solded_articles WHERE states!='deleted' GROUP BY id_article;

/* solded view using articles categories */
CREATE OR REPLACE VIEW solded_articles_user AS SELECT  articles_categories.id,sums_user.dates,sums_user.id_article,articles_categories.article_name,articles_categories.categorie,articles_categories.color,articles_categories.sizes,sums_user.quantity,articles_categories.price,articles_categories.discount,sums_user.total_price,articles_categories.small_images FROM sums_user,articles_categories WHERE sums_user.id_article=articles_categories.id_article AND articles_categories.id_shop=0;


/* purchased view using articles categories */
CREATE OR REPLACE VIEW purchased_user AS SELECT purchase.id,purchase.purchase_dates,purchase.id_article,articles_categories.article_name,articles_categories.categorie,articles_categories.color,articles_categories.sizes,purchase.quantity,articles_categories.price,articles_categories.discount,purchase.total_price,articles_categories.small_images FROM purchase,articles_categories WHERE purchase.id_article=articles_categories.id_article AND purchase.states != 'deleted';
/* /users */

/* shop stock */
CREATE OR REPLACE VIEW articles_shop AS SELECT articles.*,categories.categorie FROM articles,categories WHERE articles.id_categorie=categories.id_categorie AND states!='deleted';

/* solded shop */
CREATE TABLE solded_articles(
	id_shop INT UNSIGNED NOT NULL,
	id INT UNSIGNED NOT NULL,
	id_article INT NOT NULL,
	solded_dates TIMESTAMP,
	quantity INT,
	total_price INT,
	total_with_post INT,
	states VARCHAR(8),
	FOREIGN KEY (id_shop) REFERENCES shops(id_shop),
	FOREIGN KEY (id) REFERENCES users(id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE subscriptions(
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	subs_name VARCHAR(20),
	price_notice VARCHAR(3),
	subs_price INT UNSIGNED NOT NULL,
	text_notice VARCHAR(8),
	subs_text TEXT,
	article_limit INT UNSIGNED NOT NULL,
	admin_limit INT UNSIGNED NOT NULL,
	shops_number INT UNSIGNED NOT NULL,
	duration_notice VARCHAR(4),
	subs_duration_text VARCHAR(3),
	subs_duration INT UNSIGNED NOT NULL,
	day_bonus INT UNSIGNED NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO subscriptions(subs_name,price_notice,subs_price,text_notice,subs_text,article_limit,admin_limit,shops_number,duration_notice,subs_duration_text,subs_duration,day_bonus) 
					VALUES('小店','毎月',10000,'商品と管理者','1ショップ<br>100商品 / 10管理者',100,10,1,'契約期間','1ヶ月',1,10);

INSERT INTO subscriptions(subs_name,price_notice,subs_price,text_notice,subs_text,article_limit,admin_limit,shops_number,duration_notice,subs_duration_text,subs_duration,day_bonus)
					VALUES('一年小店','毎年',75000,'商品と管理者','1ショップ<br>1000商品 / 20管理者',1000,20,1,'契約期間','1年',12,10);

INSERT INTO subscriptions(subs_name,price_notice,subs_price,text_notice,subs_text,article_limit,admin_limit,shops_number,duration_notice,subs_duration_text,subs_duration,day_bonus)
					VALUES('ミドル','毎月',28000,'商品と管理者','３ショップ<br>300商品 / 10管理者',300,10,3,'契約期間','3ヶ月',1,15);

INSERT INTO subscriptions(subs_name,price_notice,subs_price,text_notice,subs_text,article_limit,admin_limit,shops_number,duration_notice,subs_duration_text,subs_duration,day_bonus)
					VALUES('一年ミドル','毎年',87000,'商品と管理者','３ショップ<br>3000商品 / 30管理者',3000,30,3,'契約期間','1年',12,15);

INSERT INTO subscriptions(subs_name,price_notice,subs_price,text_notice,subs_text,article_limit,admin_limit,shops_number,duration_notice,subs_duration_text,subs_duration,day_bonus)
					VALUES('大店','毎年',98000,'商品と管理者','10ショップ<br>他は無制限',1,1,10,'契約期間','1年',12,20);

CREATE TABLE user_shop_subscriptions(
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_subscription INT UNSIGNED NOT NULL,
	id_user INT UNSIGNED NOT NULL,
	validity INT UNSIGNED NOT NULL,
	subscription_date TIMESTAMP,
	limit_date TIMESTAMP,
	FOREIGN KEY (id_subscription) REFERENCES subscriptions(id),
	FOREIGN KEY (id_user) REFERENCES users(id),
	UNIQUE(id_user)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE OR REPLACE VIEW v_user_subscription AS SELECT 
	usubs.id_subscription,
	usubs.id_user,
	usubs.validity,
	usubs.subscription_date,
	usubs.limit_date,
	subscriptions.subs_name,
	subscriptions.subs_price,
	subscriptions.article_limit,
	subscriptions.admin_limit,
	subscriptions.shops_number
FROM user_shop_subscriptions AS usubs JOIN subscriptions ON usubs.id_subscription = subscriptions.id;


/* articles in shop */

/* /shop sum solded articles */
CREATE OR REPLACE VIEW sums_shop AS SELECT MAX(solded_dates) AS dates,id_article, SUM(quantity) AS quantity,SUM(total_price) AS total_price FROM solded_articles WHERE states!='deleted' GROUP BY id_article;

/* solded view using articles categories */
CREATE OR REPLACE VIEW solded_articles_shop AS SELECT  articles_categories.id_shop,articles_categories.id,sums_shop.dates,sums_shop.id_article,articles_categories.article_name,articles_categories.categorie,articles_categories.color,articles_categories.sizes,sums_shop.quantity,articles_categories.price,articles_categories.discount,sums_shop.total_price,articles_categories.small_images FROM sums_shop,articles_categories WHERE sums_shop.id_article=articles_categories.id_article;

/* /shop stock */
/* shop categories */
CREATE OR REPLACE VIEW categories_in_shop AS SELECT categories.*,articles.id_shop as id_shop FROM categories,articles WHERE categories.id_categorie = articles.id_categorie AND id_shop!=0 GROUP BY categories.id_categorie,articles.id_shop;
/* /shop categories */





INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('1','0','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');


 /* shop */
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','Aprovel','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','ビオカリプトル','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),' images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','ㇸクザキン','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','Aprovel','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','ビオカリプトル','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),' images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','ㇸクザキン','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','Aprovel','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','ビオカリプトル','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),' images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','ㇸクザキン','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','Aprovel','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','ビオカリプトル','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),' images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','ㇸクザキン','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','Aprovel','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','ビオカリプトル','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),' images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','ㇸクザキン','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','Aprovel','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','ビオカリプトル','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),' images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','ㇸクザキン','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','Aprovel','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','ビオカリプトル','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),' images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','ㇸクザキン','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','Aprovel','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','ビオカリプトル','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),' images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','ㇸクザキン','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','Aprovel','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','ビオカリプトル','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),' images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','ㇸクザキン','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','Aprovel','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','ビオカリプトル','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),' images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','ㇸクザキン','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','Aprovel','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','ビオカリプトル','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),' images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','on sale');
INSERT INTO articles(id,id_creator_shop,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)VALUES('2','1','1','14','ㇸクザキン','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','on sale');

 SELECT * FROM articles WHERE states!='deleted' AND id = '1' ORDER BY total_price asc LIMIT 0,10;
