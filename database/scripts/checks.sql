SELECT
    t.plz
FROM `bta-verwaltung-v0`.teilnehmers t
WHERE t.plz IS NOT NULL
  AND t.plz NOT IN (SELECT zipcode FROM agenturen.zipcodes_unique);
