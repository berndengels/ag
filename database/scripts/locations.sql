
INSERT IGNORE INTO states (name)
SELECT DISTINCT state FROM locations ORDER BY state;

INSERT IGNORE INTO communities (state_id, name, code)
SELECT
    s.id, l.community, l.community_code
FROM locations l
JOIN states s ON s.name = l.state
GROUP BY l.community
ORDER BY l.community;

UPDATE locations l
JOIN communities c ON c.name = l.community AND c.code = l.community_code
SET l.community_id = c.id;

UPDATE locations SET found_jc=0,found_aa=0;

SELECT
    zipcode
FROM locations
GROUP BY zipcode;
