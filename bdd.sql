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
	logo_type VARCHAR(20),
    sign_date TIMESTAMP NOT NULL,
    delete_date TIMESTAMP NULL,
    spec VARCHAR(7),
	last_seen TIMESTAMP   
)ENGINE=InnoDB DEFAULT CHARSET=utf8;



	/* alter table users add column rememberToken TEXT after email_verified_at;
	alter table users modify column email TEXT; */
/* alter table users modify column logo_type VARCHAR(20);
alter table users CHANGE password u_password TEXT;
update users set logo_type="images/kaibai_h.png" where id=2;
update users set logo_type="images/kaibai_h.png" where id=3;
update users set logo_type="images/kaibai_h.png" where id=5;
update users set logo_type="images/kaibai_f.png" where id=4; */


CREATE TABLE confirm_users(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_user INT UNSIGNED NOT NULL,
	code TEXT,
	FOREIGN KEY (id_user) REFERENCES users(id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE categories(
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
	total_price INT,
	large_images TEXT,
    small_images TEXT,
	states VARCHAR(8),
    FOREIGN KEY (id_categorie) REFERENCES categories(id_categorie),
    FOREIGN KEY (id) REFERENCES users(id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE OR REPLACE VIEW articles_categories AS SELECT articles.*,categories.categorie FROM articles,categories WHERE articles.id_categorie=categories.id_categorie;





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
CREATE OR REPLACE VIEW admin_shop AS SELECT user_shop.id,shops.id_shop,shops.shop_name,shops.phone_number,shops.shop_email,shops.creation_date,shops.delete_date,shops.descriptions,shops.shop_site,shops.shop_logo,shops.shop_picture FROM shops,user_shop WHERE shops.id_shop=user_shop.id_shop AND shops.id != user_shop.id AND user_shop.states!='blocked';

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

landernaud-ramampjean@gmail.com
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

	INSERT INTO categories VALUES(001,'ファッション');
	INSERT INTO categories VALUES(002,'ファッション小物');
	INSERT INTO categories VALUES(003,'キッズ・ベビー・玩具');
	INSERT INTO categories VALUES(004,'スポーツ・ゴルフ');
	INSERT INTO categories VALUES(005,'家電・TV・カメラ');
	INSERT INTO categories VALUES(006,'PC・スマホ・通信');
	INSERT INTO categories VALUES(007,'食品・スイーツ');
	INSERT INTO categories VALUES(008,'ドリンク・お酒');
	INSERT INTO categories VALUES(009,'インテリア・寝具');
	INSERT INTO categories VALUES(010,'本・電子書籍・音楽');
	INSERT INTO categories VALUES(011,'ゲーム・ホビー・楽器');
	INSERT INTO categories VALUES(012,'美容・コスメ・香水');
	INSERT INTO categories VALUES(013,'スキンケア');
	INSERT INTO categories VALUES(014,'ヘアケア・スタイリング');
	INSERT INTO categories VALUES(015,'ボディケア');
	INSERT INTO categories VALUES(016,'アロマ・お香');
	INSERT INTO categories VALUES(017,'ダイエット');
	INSERT INTO categories VALUES(018,'デンタルケア');
	INSERT INTO categories VALUES(019,'リラックス・マッサージ用品');
	INSERT INTO categories VALUES(020,'コンタクトレンズ・ケア用品');
	INSERT INTO categories VALUES(021,'ペット・花・DIY工具');
	INSERT INTO categories VALUES(022,'ハンドメイド');
	INSERT INTO categories VALUES(023,'日用雑貨・キッチン用品');
 
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
CREATE OR REPLACE VIEW articles_user AS SELECT articles.*,categories.categorie FROM articles,categories WHERE articles.id_categorie=categories.id_categorie AND articles.id_shop=0;

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
	duration_notice VARCHAR(4),
	subs_duration_text VARCHAR(3),
	subs_duration INT UNSIGNED NOT NULL,
	day_bonus INT UNSIGNED NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO subscriptions(subs_name,price_notice,subs_price,text_notice,subs_text,article_limit,admin_limit,duration_notice,subs_duration_text,subs_duration,day_bonus) 
					VALUES('小店','毎月',10000,'商品と管理者','100商品 / 10管理者',100,10,'契約期間','1ヶ月',1,10);

INSERT INTO subscriptions(subs_name,price_notice,subs_price,text_notice,subs_text,article_limit,admin_limit,duration_notice,subs_duration_text,subs_duration,day_bonus)
					VALUES('一年小店','毎年',75000,'商品と管理者','1000商品 / 20管理者',1000,20,'契約期間','1年',12,10);

INSERT INTO subscriptions(subs_name,price_notice,subs_price,text_notice,subs_text,article_limit,admin_limit,duration_notice,subs_duration_text,subs_duration,day_bonus)
					VALUES('ミドル','毎月',28000,'商品と管理者','300商品 / 10管理者',300,10,'契約期間','3ヶ月',1,15);

INSERT INTO subscriptions(subs_name,price_notice,subs_price,text_notice,subs_text,article_limit,admin_limit,duration_notice,subs_duration_text,subs_duration,day_bonus)
					VALUES('一年ミドル','毎年',87000,'商品と管理者','3000商品 / 30管理者',3000,30,'契約期間','1年',12,15);

INSERT INTO subscriptions(subs_name,price_notice,subs_price,text_notice,subs_text,article_limit,admin_limit,duration_notice,subs_duration_text,subs_duration,day_bonus)
					VALUES('大店','毎年',98000,'商品と管理者','無制限',1,1,'契約期間','1年',12,20);

CREATE TABLE user_shop_subscriptions(
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_subscription INT UNSIGNED NOT NULL,
	id_user INT UNSIGNED NOT NULL,
	id_shop INT UNSIGNED,
	validity INT UNSIGNED NOT NULL,
	subscription_date TIMESTAMP,
	limit_date TIMESTAMP,
	FOREIGN KEY (id_subscription) REFERENCES subscriptions(id),
	FOREIGN KEY (id_user) REFERENCES users(id),
	UNIQUE(id_user),
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* /shop sum solded articles */
CREATE OR REPLACE VIEW sums_shop AS SELECT MAX(solded_dates) AS dates,id_article, SUM(quantity) AS quantity,SUM(total_price) AS total_price FROM solded_articles WHERE states!='deleted' GROUP BY id_article;

/* solded view using articles categories */
CREATE OR REPLACE VIEW solded_articles_shop AS SELECT  articles_categories.id_shop,articles_categories.id,sums_shop.dates,sums_shop.id_article,articles_categories.article_name,articles_categories.categorie,articles_categories.color,articles_categories.sizes,sums_shop.quantity,articles_categories.price,articles_categories.discount,sums_shop.total_price,articles_categories.small_images FROM sums_shop,articles_categories WHERE sums_shop.id_article=articles_categories.id_article;

/* /shop stock */
/* shop categories */
CREATE OR REPLACE VIEW categories_in_shop AS SELECT categories.*,articles.id_shop as id_shop FROM categories,articles WHERE categories.id_categorie = articles.id_categorie AND id_shop!=0 GROUP BY categories.id_categorie,articles.id_shop;
/* /shop categories */





INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');
INSERT INTO articles(id,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('1','5','靴','red','56','5','20000',NOW(),'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',calctotal('5','2000'),'images/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','images/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwOUU2Ny50bXAxNjUwNjU1NjU5.jpg','on sale');


 /* shop */
INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','Aprovel','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','on sale');

INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','ビオカリプトル','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),' images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','on sale');

INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','ㇸクザキン','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','on sale');
INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','Aprovel','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','on sale');

INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','ビオカリプトル','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),' images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','on sale');

INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','ㇸクザキン','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','on sale');
INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','Aprovel','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','on sale');

INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','ビオカリプトル','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),' images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','on sale');

INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','ㇸクザキン','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','on sale');
INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','Aprovel','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','on sale');

INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','ビオカリプトル','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),' images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','on sale');

INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','ㇸクザキン','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','on sale');
INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','Aprovel','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','on sale');

INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','ビオカリプトル','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),' images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','on sale');

INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','ㇸクザキン','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','on sale');
INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','Aprovel','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','on sale');

INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','ビオカリプトル','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),' images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','on sale');

INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','ㇸクザキン','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','on sale');
INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','Aprovel','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','on sale');

INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','ビオカリプトル','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),' images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','on sale');

INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','ㇸクザキン','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','on sale');
INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','Aprovel','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','on sale');

INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','ビオカリプトル','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),' images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','on sale');

INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','ㇸクザキン','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','on sale');
INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','Aprovel','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','on sale');

INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','ビオカリプトル','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),' images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','on sale');

INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','ㇸクザキン','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','on sale');
INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','Aprovel','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','on sale');

INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','ビオカリプトル','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),' images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','on sale');

INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','ㇸクザキン','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','on sale');
INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','Aprovel','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwODA0Ri50bXAxNjUxNDkzODQx.jpg','on sale');

INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','ビオカリプトル','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),' images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwQkZFMS50bXAxNjUxNjA3OTU2.jpg','on sale');

INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states)
 VALUES('2','1','14','ㇸクザキン','Select color','10cp','5','10000',NOW(),'いい薬です。<br/>
買ってください。',calctotal('5','1000'),'images/shops/Kimito/big/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','images/shops/Kimito/small/QzpcVXNlcnNcS2Fpem9rdVxBcHBEYXRhXExvY2FsXFRlbXBccGhwRkFCQy50bXAxNjUxNjA4MTY3.jpg','on sale');


 SELECT * FROM articles WHERE states!='deleted' AND id = '1' ORDER BY total_price asc LIMIT 0,10;
