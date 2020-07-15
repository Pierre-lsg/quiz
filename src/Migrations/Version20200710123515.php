<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200710123515 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_DADD4A251E27F6BF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__answer AS SELECT id, question_id, label, value FROM answer');
        $this->addSql('DROP TABLE answer');
        $this->addSql('CREATE TABLE answer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, question_id INTEGER NOT NULL, label CLOB NOT NULL COLLATE BINARY, value INTEGER NOT NULL, CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO answer (id, question_id, label, value) SELECT id, question_id, label, value FROM __temp__answer');
        $this->addSql('DROP TABLE __temp__answer');
        $this->addSql('CREATE INDEX IDX_DADD4A251E27F6BF ON answer (question_id)');
        $this->addSql('DROP INDEX IDX_AB55E24F853CD175');
        $this->addSql('DROP INDEX IDX_AB55E24F99E6F5DF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__participation AS SELECT id, player_id, quiz_id, played_at, result FROM participation');
        $this->addSql('DROP TABLE participation');
        $this->addSql('CREATE TABLE participation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, player_id INTEGER NOT NULL, quiz_id INTEGER NOT NULL, played_at DATETIME NOT NULL, result VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_AB55E24F99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_AB55E24F853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO participation (id, player_id, quiz_id, played_at, result) SELECT id, player_id, quiz_id, played_at, result FROM __temp__participation');
        $this->addSql('DROP TABLE __temp__participation');
        $this->addSql('CREATE INDEX IDX_AB55E24F853CD175 ON participation (quiz_id)');
        $this->addSql('CREATE INDEX IDX_AB55E24F99E6F5DF ON participation (player_id)');
        $this->addSql('DROP INDEX IDX_45DDDCFB6ACE3B73');
        $this->addSql('DROP INDEX IDX_45DDDCFBAA334807');
        $this->addSql('CREATE TEMPORARY TABLE __temp__player_answer AS SELECT id, answer_id, participation_id FROM player_answer');
        $this->addSql('DROP TABLE player_answer');
        $this->addSql('CREATE TABLE player_answer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, answer_id INTEGER NOT NULL, participation_id INTEGER NOT NULL, CONSTRAINT FK_45DDDCFBAA334807 FOREIGN KEY (answer_id) REFERENCES answer (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_45DDDCFB6ACE3B73 FOREIGN KEY (participation_id) REFERENCES participation (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO player_answer (id, answer_id, participation_id) SELECT id, answer_id, participation_id FROM __temp__player_answer');
        $this->addSql('DROP TABLE __temp__player_answer');
        $this->addSql('CREATE INDEX IDX_45DDDCFB6ACE3B73 ON player_answer (participation_id)');
        $this->addSql('CREATE INDEX IDX_45DDDCFBAA334807 ON player_answer (answer_id)');
        $this->addSql('DROP INDEX IDX_B6F7494E853CD175');
        $this->addSql('CREATE TEMPORARY TABLE __temp__question AS SELECT id, quiz_id, label, picture FROM question');
        $this->addSql('DROP TABLE question');
        $this->addSql('CREATE TABLE question (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, quiz_id INTEGER NOT NULL, label CLOB NOT NULL COLLATE BINARY, picture VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_B6F7494E853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO question (id, quiz_id, label, picture) SELECT id, quiz_id, label, picture FROM __temp__question');
        $this->addSql('DROP TABLE __temp__question');
        $this->addSql('CREATE INDEX IDX_B6F7494E853CD175 ON question (quiz_id)');
        $this->addSql('DROP INDEX IDX_F64DD088853CD175');
        $this->addSql('CREATE TEMPORARY TABLE __temp__result_comment AS SELECT id, quiz_id, lower_bound, upper_bound, comment, reward FROM result_comment');
        $this->addSql('DROP TABLE result_comment');
        $this->addSql('CREATE TABLE result_comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, quiz_id INTEGER NOT NULL, lower_bound INTEGER NOT NULL, upper_bound INTEGER NOT NULL, comment CLOB NOT NULL COLLATE BINARY, reward VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_F64DD088853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO result_comment (id, quiz_id, lower_bound, upper_bound, comment, reward) SELECT id, quiz_id, lower_bound, upper_bound, comment, reward FROM __temp__result_comment');
        $this->addSql('DROP TABLE __temp__result_comment');
        $this->addSql('CREATE INDEX IDX_F64DD088853CD175 ON result_comment (quiz_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_DADD4A251E27F6BF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__answer AS SELECT id, question_id, label, value FROM answer');
        $this->addSql('DROP TABLE answer');
        $this->addSql('CREATE TABLE answer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, question_id INTEGER NOT NULL, label CLOB NOT NULL, value INTEGER NOT NULL)');
        $this->addSql('INSERT INTO answer (id, question_id, label, value) SELECT id, question_id, label, value FROM __temp__answer');
        $this->addSql('DROP TABLE __temp__answer');
        $this->addSql('CREATE INDEX IDX_DADD4A251E27F6BF ON answer (question_id)');
        $this->addSql('DROP INDEX IDX_AB55E24F99E6F5DF');
        $this->addSql('DROP INDEX IDX_AB55E24F853CD175');
        $this->addSql('CREATE TEMPORARY TABLE __temp__participation AS SELECT id, player_id, quiz_id, played_at, result FROM participation');
        $this->addSql('DROP TABLE participation');
        $this->addSql('CREATE TABLE participation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, player_id INTEGER NOT NULL, quiz_id INTEGER NOT NULL, played_at DATETIME NOT NULL, result VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO participation (id, player_id, quiz_id, played_at, result) SELECT id, player_id, quiz_id, played_at, result FROM __temp__participation');
        $this->addSql('DROP TABLE __temp__participation');
        $this->addSql('CREATE INDEX IDX_AB55E24F99E6F5DF ON participation (player_id)');
        $this->addSql('CREATE INDEX IDX_AB55E24F853CD175 ON participation (quiz_id)');
        $this->addSql('DROP INDEX IDX_45DDDCFBAA334807');
        $this->addSql('DROP INDEX IDX_45DDDCFB6ACE3B73');
        $this->addSql('CREATE TEMPORARY TABLE __temp__player_answer AS SELECT id, answer_id, participation_id FROM player_answer');
        $this->addSql('DROP TABLE player_answer');
        $this->addSql('CREATE TABLE player_answer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, answer_id INTEGER NOT NULL, participation_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO player_answer (id, answer_id, participation_id) SELECT id, answer_id, participation_id FROM __temp__player_answer');
        $this->addSql('DROP TABLE __temp__player_answer');
        $this->addSql('CREATE INDEX IDX_45DDDCFBAA334807 ON player_answer (answer_id)');
        $this->addSql('CREATE INDEX IDX_45DDDCFB6ACE3B73 ON player_answer (participation_id)');
        $this->addSql('DROP INDEX IDX_B6F7494E853CD175');
        $this->addSql('CREATE TEMPORARY TABLE __temp__question AS SELECT id, quiz_id, label, picture FROM question');
        $this->addSql('DROP TABLE question');
        $this->addSql('CREATE TABLE question (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, quiz_id INTEGER NOT NULL, label CLOB NOT NULL, picture VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO question (id, quiz_id, label, picture) SELECT id, quiz_id, label, picture FROM __temp__question');
        $this->addSql('DROP TABLE __temp__question');
        $this->addSql('CREATE INDEX IDX_B6F7494E853CD175 ON question (quiz_id)');
        $this->addSql('DROP INDEX IDX_F64DD088853CD175');
        $this->addSql('CREATE TEMPORARY TABLE __temp__result_comment AS SELECT id, quiz_id, lower_bound, upper_bound, comment, reward FROM result_comment');
        $this->addSql('DROP TABLE result_comment');
        $this->addSql('CREATE TABLE result_comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, quiz_id INTEGER NOT NULL, lower_bound INTEGER NOT NULL, upper_bound INTEGER NOT NULL, comment CLOB NOT NULL, reward VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO result_comment (id, quiz_id, lower_bound, upper_bound, comment, reward) SELECT id, quiz_id, lower_bound, upper_bound, comment, reward FROM __temp__result_comment');
        $this->addSql('DROP TABLE __temp__result_comment');
        $this->addSql('CREATE INDEX IDX_F64DD088853CD175 ON result_comment (quiz_id)');
    }
}
