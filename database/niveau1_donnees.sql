INSERT INTO plat (nom, prix, id_categorie) VALUES
('mofo gasy', 300, 4);

INSERT INTO plat (nom, prix, id_categorie) VALUES
('Koba', 1000, 4),
('mokary', 600, 4),
('godrogodro', 500, 4),
('menakely', 300, 4);

UPDATE plat SET disponible = false WHERE id = 15;

UPDATE plat SET description = 'Petits gâteaux traditionnels à base de farine de riz et de sucre, cuits dans un moule spécifique, parfaits pour le petit-déjeuner.' WHERE id = 11;

UPDATE plat SET description = 'Spécialité traditionnelle à base de pâte de riz, de cacahuètes concassées et de cassonade, enveloppée et cuite dans des feuilles de bananier.' WHERE id = 12;

UPDATE plat SET description = 'Gâteau traditionnel à base de farine de riz et souvent de lait de coco, cuit dans un moule alvéolé.' WHERE id = 13;

UPDATE plat SET description = 'Gâteau traditionnel malgache moelleux et parfumé, préparé à base de farine de riz, de lait de coco et de sucre roux.' WHERE id = 14;

UPDATE plat SET description = 'Beignets traditionnels malgaches frits, croustillants à l''extérieur et moelleux à l''intérieur, préparés à base de farine de riz et de sucre.' WHERE id = 15;

-- Insertion a prix = 0 Ar
INSERT INTO plat (nom, prix, id_categorie) VALUES
('Tsy mety', 0, 4);

-- Nom existant
INSERT INTO plat (nom, prix, id_categorie) VALUES
('mokary', 100000000, 4);