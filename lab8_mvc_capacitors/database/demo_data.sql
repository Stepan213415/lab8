-- Optional test records for a NEW, otherwise empty database.
USE capacitors_db;
INSERT IGNORE INTO capacitor_types (name) VALUES
    ('Електролітичний'), ('Керамічний'), ('Плівковий');
INSERT INTO capacitors (designation, type_id, capacitance_uf, voltage_v)
SELECT 'К50-35', id, 47.0, 25.0 FROM capacitor_types
WHERE name='Електролітичний' AND NOT EXISTS (SELECT 1 FROM capacitors WHERE designation='К50-35');
INSERT INTO capacitors (designation, type_id, capacitance_uf, voltage_v)
SELECT 'К10-17', id, 0.10, 50.0 FROM capacitor_types
WHERE name='Керамічний' AND NOT EXISTS (SELECT 1 FROM capacitors WHERE designation='К10-17');
INSERT INTO capacitors (designation, type_id, capacitance_uf, voltage_v)
SELECT 'К73-17', id, 1.0, 63.0 FROM capacitor_types
WHERE name='Плівковий' AND NOT EXISTS (SELECT 1 FROM capacitors WHERE designation='К73-17');
INSERT INTO parameters (capacitor_id, name, value)
SELECT id, 'Температура', '105°C' FROM capacitors c WHERE designation='К50-35'
AND NOT EXISTS (SELECT 1 FROM parameters p WHERE p.capacitor_id=c.id AND p.name='Температура');
