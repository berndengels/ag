SELECT
    o.id,
    o.name
FROM opt_arbeitsagenturen o
LEFT JOIN arbeitsagenturen a ON a.name = o.name
WHERE a.name IS NULL
ORDER BY name;

/*
1482	Agentur für Arbeit Altena
1087	Agentur für Arbeit Aue
1444	Agentur für Arbeit Birkenfeld
1509	Agentur für Arbeit Erbach
1089	Agentur für Arbeit Glauchau
1396	Agentur für Arbeit Greven
1484	Agentur für Arbeit Plettenberg
1620	Agentur für Arbeit Waldkirch
*/

INSERT IGNORE INTO opt_arbeitsagenturen_2024
    (name,info,url,street,postcode,city,email,fon,opening_time,by_agency)
SELECT
    name,info,url,street,postcode,city,email,fon,opening_time,1
FROM arbeitsagenturen
ORDER BY postcode;
