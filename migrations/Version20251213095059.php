<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251213095059 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, content VARCHAR(255) NOT NULL, is_deleted VARCHAR(255) NOT NULL, user_id INT DEFAULT NULL, post_id INT DEFAULT NULL, comment_id INT DEFAULT NULL, upvote_user_id INT DEFAULT NULL, downvote_user_id INT DEFAULT NULL, file_id INT DEFAULT NULL, INDEX IDX_9474526CA76ED395 (user_id), INDEX IDX_9474526C4B89032C (post_id), INDEX IDX_9474526CF8697D13 (comment_id), INDEX IDX_9474526C528A9209 (upvote_user_id), INDEX IDX_9474526C3BC38922 (downvote_user_id), INDEX IDX_9474526C93CB796C (file_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 ENGINE=MyISAM');
        $this->addSql('CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) NOT NULL, original_name VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 ENGINE=MyISAM');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL, is_deleted TINYINT(1) NOT NULL, user_id INT DEFAULT NULL, upvote_user_id INT DEFAULT NULL, downvote_user_id INT DEFAULT NULL, INDEX IDX_5A8A6C8DA76ED395 (user_id), INDEX IDX_5A8A6C8D528A9209 (upvote_user_id), INDEX IDX_5A8A6C8D3BC38922 (downvote_user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 ENGINE=MyISAM');
        $this->addSql('CREATE TABLE post_file (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, file_id INT DEFAULT NULL, INDEX IDX_45CA511B4B89032C (post_id), INDEX IDX_45CA511B93CB796C (file_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 ENGINE=MyISAM');
        $this->addSql('CREATE TABLE token (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) NOT NULL, expires_at DATETIME NOT NULL, user_id INT DEFAULT NULL, INDEX IDX_5F37A13BA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 ENGINE=MyISAM');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C528A9209 FOREIGN KEY (upvote_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C3BC38922 FOREIGN KEY (downvote_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C93CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D528A9209 FOREIGN KEY (upvote_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D3BC38922 FOREIGN KEY (downvote_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post_file ADD CONSTRAINT FK_45CA511B4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE post_file ADD CONSTRAINT FK_45CA511B93CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE token ADD CONSTRAINT FK_5F37A13BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD enabled TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4B89032C');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF8697D13');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C528A9209');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C3BC38922');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C93CB796C');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D528A9209');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D3BC38922');
        $this->addSql('ALTER TABLE post_file DROP FOREIGN KEY FK_45CA511B4B89032C');
        $this->addSql('ALTER TABLE post_file DROP FOREIGN KEY FK_45CA511B93CB796C');
        $this->addSql('ALTER TABLE token DROP FOREIGN KEY FK_5F37A13BA76ED395');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE post_file');
        $this->addSql('DROP TABLE token');
        $this->addSql('ALTER TABLE user DROP enabled');
    }
}
