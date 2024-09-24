<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200913121249 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company (uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', postal_code_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', vat VARCHAR(15) NOT NULL, name VARCHAR(100) NOT NULL, description VARCHAR(255) DEFAULT NULL, website VARCHAR(100) DEFAULT NULL, phone_number1 VARCHAR(20) NOT NULL, phone_number2 VARCHAR(20) DEFAULT NULL, email VARCHAR(100) NOT NULL, street_name VARCHAR(100) NOT NULL, number INT DEFAULT NULL, floor VARCHAR(20) DEFAULT NULL, business_days TINYINT(1) NOT NULL, blocked TINYINT(1) NOT NULL, blocked_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_4FBF094FBDBA6A61 (postal_code_id), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country (uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(50) NOT NULL, created_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_5373C9665E237E06 (name), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country_region (uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', country_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(50) NOT NULL, created_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_4F1A1A055E237E06 (name), INDEX IDX_4F1A1A05F92F3E70 (country_id), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country_region_town (uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', region_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(50) NOT NULL, created_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_80D7FA585E237E06 (name), INDEX IDX_80D7FA5898260155 (region_id), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE town_postal_codes (town_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', postal_code_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_444E5A6975E23604 (town_id), INDEX IDX_444E5A69BDBA6A61 (postal_code_id), PRIMARY KEY(town_id, postal_code_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country_region_town_postal_code (uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', value VARCHAR(8) NOT NULL, created_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_3EA6D6ED1D775834 (value), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE department (uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', work_place_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(150) NOT NULL, description VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(20) NOT NULL, phone_extension INT DEFAULT NULL, blocked TINYINT(1) NOT NULL, created_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_CD1DE18AD8132845 (work_place_id), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(150) NOT NULL, link VARCHAR(255) DEFAULT NULL, created_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', delete_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE holiday (uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', work_place_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', holiday_name VARCHAR(150) NOT NULL, start_day DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_day DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_DC9AB234D8132845 (work_place_id), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE request (uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', status_request_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', type_request_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', description VARCHAR(255) NOT NULL, request_period_start DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', request_period_end DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_3B978F9FA76ED395 (user_id), INDEX IDX_3B978F9F432D5C23 (status_request_id), INDEX IDX_3B978F9F5F7D6E80 (type_request_id), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE requests_documents (request_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', document_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_2B53093F427EB8A5 (request_id), INDEX IDX_2B53093FC33F7837 (document_id), PRIMARY KEY(request_id, document_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_57698A6A5E237E06 (name), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status_request (uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, created_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_3347F81A5E237E06 (name), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_request (uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(100) NOT NULL, discount_days TINYINT(1) NOT NULL, created_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_13B6E06A5E237E06 (name), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', postal_code_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', department_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', work_position_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', company_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(100) NOT NULL, lastname VARCHAR(100) NOT NULL, dni VARCHAR(20) NOT NULL, available_days INT NOT NULL, accumulated_days INT NOT NULL, social_security_number VARCHAR(255) NOT NULL, phone_number VARCHAR(20) NOT NULL, email_address VARCHAR(100) NOT NULL, password VARCHAR(100) NOT NULL, incorporation_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', street_name VARCHAR(100) NOT NULL, number INT DEFAULT NULL, floor VARCHAR(20) DEFAULT NULL, blocked TINYINT(1) NOT NULL, token_recovery VARCHAR(255) DEFAULT NULL, token_recovery_validator VARCHAR(255) DEFAULT NULL, token_recovery_expiration_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', blocked_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8D93D649B08E074E (email_address), UNIQUE INDEX UNIQ_8D93D649871C4E4A (token_recovery), UNIQUE INDEX UNIQ_8D93D6494E538E31 (token_recovery_validator), INDEX IDX_8D93D649BDBA6A61 (postal_code_id), INDEX IDX_8D93D649AE80F5DF (department_id), INDEX IDX_8D93D649C245CE85 (work_position_id), INDEX IDX_8D93D649979B1AD6 (company_id), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_roles (user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', role_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_54FCD59FA76ED395 (user_id), INDEX IDX_54FCD59FD60322AC (role_id), PRIMARY KEY(user_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_place (uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', company_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', postal_code_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(150) NOT NULL, description VARCHAR(255) DEFAULT NULL, phone_number1 VARCHAR(20) NOT NULL, phone_number2 VARCHAR(20) DEFAULT NULL, email VARCHAR(100) NOT NULL, blocked TINYINT(1) NOT NULL, permit_accumulate TINYINT(1) NOT NULL, month_permitted_to_accumulate INT DEFAULT NULL, holiday_start_year DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', holiday_end_year DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', street_name VARCHAR(100) NOT NULL, number INT DEFAULT NULL, floor VARCHAR(20) DEFAULT NULL, created_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', blocked_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_5CE628E2979B1AD6 (company_id), INDEX IDX_5CE628E2BDBA6A61 (postal_code_id), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_position (uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', department_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(100) NOT NULL, head_department TINYINT(1) NOT NULL, created_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_93996EA9AE80F5DF (department_id), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FBDBA6A61 FOREIGN KEY (postal_code_id) REFERENCES country_region_town_postal_code (uuid)');
        $this->addSql('ALTER TABLE country_region ADD CONSTRAINT FK_4F1A1A05F92F3E70 FOREIGN KEY (country_id) REFERENCES country (uuid)');
        $this->addSql('ALTER TABLE country_region_town ADD CONSTRAINT FK_80D7FA5898260155 FOREIGN KEY (region_id) REFERENCES country_region (uuid)');
        $this->addSql('ALTER TABLE town_postal_codes ADD CONSTRAINT FK_444E5A6975E23604 FOREIGN KEY (town_id) REFERENCES country_region_town (uuid)');
        $this->addSql('ALTER TABLE town_postal_codes ADD CONSTRAINT FK_444E5A69BDBA6A61 FOREIGN KEY (postal_code_id) REFERENCES country_region_town_postal_code (uuid)');
        $this->addSql('ALTER TABLE department ADD CONSTRAINT FK_CD1DE18AD8132845 FOREIGN KEY (work_place_id) REFERENCES work_place (uuid)');
        $this->addSql('ALTER TABLE holiday ADD CONSTRAINT FK_DC9AB234D8132845 FOREIGN KEY (work_place_id) REFERENCES work_place (uuid)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FA76ED395 FOREIGN KEY (user_id) REFERENCES user (uuid)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9F432D5C23 FOREIGN KEY (status_request_id) REFERENCES status_request (uuid)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9F5F7D6E80 FOREIGN KEY (type_request_id) REFERENCES type_request (uuid)');
        $this->addSql('ALTER TABLE requests_documents ADD CONSTRAINT FK_2B53093F427EB8A5 FOREIGN KEY (request_id) REFERENCES request (uuid)');
        $this->addSql('ALTER TABLE requests_documents ADD CONSTRAINT FK_2B53093FC33F7837 FOREIGN KEY (document_id) REFERENCES document (uuid)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649BDBA6A61 FOREIGN KEY (postal_code_id) REFERENCES country_region_town_postal_code (uuid)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649AE80F5DF FOREIGN KEY (department_id) REFERENCES department (uuid)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649C245CE85 FOREIGN KEY (work_position_id) REFERENCES work_position (uuid)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649979B1AD6 FOREIGN KEY (company_id) REFERENCES company (uuid)');
        $this->addSql('ALTER TABLE user_roles ADD CONSTRAINT FK_54FCD59FA76ED395 FOREIGN KEY (user_id) REFERENCES user (uuid)');
        $this->addSql('ALTER TABLE user_roles ADD CONSTRAINT FK_54FCD59FD60322AC FOREIGN KEY (role_id) REFERENCES role (uuid)');
        $this->addSql('ALTER TABLE work_place ADD CONSTRAINT FK_5CE628E2979B1AD6 FOREIGN KEY (company_id) REFERENCES company (uuid)');
        $this->addSql('ALTER TABLE work_place ADD CONSTRAINT FK_5CE628E2BDBA6A61 FOREIGN KEY (postal_code_id) REFERENCES country_region_town_postal_code (uuid)');
        $this->addSql('ALTER TABLE work_position ADD CONSTRAINT FK_93996EA9AE80F5DF FOREIGN KEY (department_id) REFERENCES department (uuid)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649979B1AD6');
        $this->addSql('ALTER TABLE work_place DROP FOREIGN KEY FK_5CE628E2979B1AD6');
        $this->addSql('ALTER TABLE country_region DROP FOREIGN KEY FK_4F1A1A05F92F3E70');
        $this->addSql('ALTER TABLE country_region_town DROP FOREIGN KEY FK_80D7FA5898260155');
        $this->addSql('ALTER TABLE town_postal_codes DROP FOREIGN KEY FK_444E5A6975E23604');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FBDBA6A61');
        $this->addSql('ALTER TABLE town_postal_codes DROP FOREIGN KEY FK_444E5A69BDBA6A61');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649BDBA6A61');
        $this->addSql('ALTER TABLE work_place DROP FOREIGN KEY FK_5CE628E2BDBA6A61');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649AE80F5DF');
        $this->addSql('ALTER TABLE work_position DROP FOREIGN KEY FK_93996EA9AE80F5DF');
        $this->addSql('ALTER TABLE requests_documents DROP FOREIGN KEY FK_2B53093FC33F7837');
        $this->addSql('ALTER TABLE requests_documents DROP FOREIGN KEY FK_2B53093F427EB8A5');
        $this->addSql('ALTER TABLE user_roles DROP FOREIGN KEY FK_54FCD59FD60322AC');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9F432D5C23');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9F5F7D6E80');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9FA76ED395');
        $this->addSql('ALTER TABLE user_roles DROP FOREIGN KEY FK_54FCD59FA76ED395');
        $this->addSql('ALTER TABLE department DROP FOREIGN KEY FK_CD1DE18AD8132845');
        $this->addSql('ALTER TABLE holiday DROP FOREIGN KEY FK_DC9AB234D8132845');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649C245CE85');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE country_region');
        $this->addSql('DROP TABLE country_region_town');
        $this->addSql('DROP TABLE town_postal_codes');
        $this->addSql('DROP TABLE country_region_town_postal_code');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE holiday');
        $this->addSql('DROP TABLE request');
        $this->addSql('DROP TABLE requests_documents');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE status_request');
        $this->addSql('DROP TABLE type_request');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_roles');
        $this->addSql('DROP TABLE work_place');
        $this->addSql('DROP TABLE work_position');
    }
}
