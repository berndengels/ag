INSERT INTO `opt_jobcenters`(
    name,
    info,
    url,
    street,
    postcode,
    city,
    email,
    fon,
    opening_time
)
SELECT
    DISTINCT name,
    info,
    url,
    street,
    postcode,
    city,
    email,
    fon,
    opening_time
FROM `jobcenters`;

INSERT INTO `opt_arbeitsagenturen`(
    name,
    info,
    url,
    street,
    postcode,
    city,
    email,
    fon,
    opening_time
)
SELECT
    DISTINCT name,
    info,
    url,
    street,
    postcode,
    city,
    email,
    fon,
    opening_time
FROM `arbeitsagenturen`;

INSERT INTO `customer_jobcenters` (
    customer_postcode,
    jobcenter_id
)
SELECT
    DISTINCT l.zipcode customer_postcode,
             oj.id jobcenter_id
FROM `locations` l
         JOIN jobcenters j ON j.customer_postcode=l.zipcode
         JOIN opt_jobcenters oj ON oj.name=j.name AND oj.postcode=j.postcode
GROUP BY j.name,j.postcode
ORDER BY l.zipcode;

INSERT INTO `customer_arbeitsagenturen` (
    customer_postcode,
    arbeitsagentur_id
)
SELECT
    DISTINCT l.zipcode customer_postcode,
    oa.id arbeitsagentur_id
FROM `locations` l
JOIN arbeitsagenturen a ON a.customer_postcode=l.zipcode
JOIN opt_arbeitsagenturen oa ON oa.name=a.name AND oa.postcode=s.postcode
GROUP BY a.name,a.postcode
ORDER BY l.zipcode;

UPDATE customer_postcodes SET jobcenter_id=oj.id
WHERE cp.customer_postcode=(
    SELECT
        j.customer_postcode
    FROM opt_jobcenters oj
    JOIN jobcenters j ON j.name=oj.name
    GROUP BY oj.id
    WHERE j.customer_postcode=SELECT customer_postcode FROM customer_postcodes WHERE j.customer_postcode
);

-- locations not found jobcenters on https://web.arbeitsagentur.de/portal/metasuche/suche/dienststellen
SELECT
    CONCAT(l.place,' ',l.zipcode) ort
FROM locations l USE INDEX (l)
WHERE NOT EXISTS (SELECT customer_postcode FROM jobcenters j WHERE j.customer_postcode=l.zipcode);

-- locations not found arbeitsagenturen on https://web.arbeitsagentur.de/portal/metasuche/suche/dienststellen
SELECT
    CONCAT(l.place,' ',l.zipcode) ort
FROM locations l USE INDEX (l)
WHERE NOT EXISTS (SELECT customer_postcode FROM arbeitsagenturen a USE INDEX (a) WHERE a.customer_postcode=l.zipcode);


-- locations zipcodes was not in zip_coordinates: 105, 8308 unique plz total
SELECT
    CONCAT(l.place,' ',l.zipcode) ort
FROM locations l USE INDEX (l)
WHERE NOT EXISTS (SELECT zipcode FROM zip_coordinates z WHERE z.zipcode=l.zipcode);
-- id	select_type	table	type	possible_keys	key	key_len	ref	rows	Extra
-- 1	PRIMARY	l	ALL	NULL	NULL	NULL	NULL	16479	Using where
-- 2	DEPENDENT SUBQUERY	z	index	zipcode	zipcode	42	NULL	17037	Using where; Using index

-- zip_coordinates zipcodes was not in locations: 105, 8273 unique plz total
SELECT
    CONCAT(z.name,' ',z.zipcode) ort
FROM zip_coordinates z USE INDEX (z)
WHERE NOT EXISTS (SELECT zipcode FROM locations l WHERE l.zipcode=z.zipcode);
-- id	select_type	table	type	possible_keys	key	key_len	ref	rows	Extra
-- 1	PRIMARY	z	ALL	NULL	NULL	NULL	NULL	17037	Using where
-- 2	DEPENDENT SUBQUERY	l	index_subquery	zipcode	zipcode	4	func	1	Using index; Using where

-- import extra_locations
INSERT INTO extra_locations (zipcode, name, lat, lon)
SELECT
    z.zipcode,
    z.name,
    z.lat,
    z.lon
FROM zip_coordinates z USE INDEX (z)
WHERE NOT EXISTS (SELECT zipcode FROM locations l WHERE l.zipcode=z.zipcode);

INSERT INTO extra_locations (zipcode, name, lat, lon)
SELECT
    l.zipcode,
    l.place,
    l.latitude,
    l.longitude
FROM locations l USE INDEX (l)
WHERE NOT EXISTS (SELECT zipcode FROM zip_coordinates z WHERE z.zipcode=l.zipcode)
ORDER BY l.zipcode;
