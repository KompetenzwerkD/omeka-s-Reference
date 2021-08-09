CREATE TABLE reference (id INT AUTO_INCREMENT NOT NULL, item_id INT DEFAULT NULL, bibl_id INT DEFAULT NULL, ref VARCHAR(190) DEFAULT NULL, INDEX IDX_AEA34913126F525E (item_id), INDEX IDX_AEA34913A5BCAC94 (bibl_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;
CREATE TABLE reference_setup (id INT AUTO_INCREMENT NOT NULL, resource_template_id INT DEFAULT NULL, INDEX IDX_F3BC362016131EA (resource_template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;
ALTER TABLE reference ADD CONSTRAINT FK_AEA34913126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE SET NULL;
ALTER TABLE reference ADD CONSTRAINT FK_AEA34913A5BCAC94 FOREIGN KEY (bibl_id) REFERENCES item (id) ON DELETE SET NULL;
ALTER TABLE reference_setup ADD CONSTRAINT FK_F3BC362016131EA FOREIGN KEY (resource_template_id) REFERENCES resource_template (id) ON DELETE SET NULL;