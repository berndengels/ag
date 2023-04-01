-- Jobcenter Meißen
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
