
INSERT IGNORE INTO customer_agencies
    (agency_type, agency_id, postcode)
SELECT
    'App\\Models\\EmploymentAgency',
    o.id,
    a.customer_postcode
FROM arbeitsagenturen a
JOIN opt_arbeitsagenturen_2024 o ON o.name = a.name
ORDER BY customer_postcode;

INSERT IGNORE INTO customer_agencies
(agency_type, agency_id, postcode)
SELECT
    'App\\Models\\JobCentre',
    o.id,
    j.customer_postcode
FROM jobcenters j
JOIN opt_jobcenters_2024 o ON o.name = j.name
ORDER BY customer_postcode;
