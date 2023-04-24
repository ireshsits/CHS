ALTER TABLE complainants ADD "TITLE" VARCHAR2(255) DEFAULT 'Mr';
ALTER TABLE complainants ADD CONSTRAINT "COMPLAINANTS_TITLE_CK" CHECK (title in ('Mr', 'Mrs', 'Ms', 'Mstr', 'Miss', 'Prof', 'Dr', 'Rev')) ENABLE;