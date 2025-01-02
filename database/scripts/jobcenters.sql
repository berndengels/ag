SELECT
    o.name
FROM opt_jobcenters o
LEFT JOIN jobcenters j ON j.name = o.name
WHERE j.name IS NULL
ORDER BY name;
/*
541	Jena Stadt (jenarbeit) (Zugelassener kommunaler Träger)
617	Jobcenter Landkreis Rotenburg (Wümme) (Zugelassener kommunaler Träger)
574	Jobcenter Vorpommern - Greifswald Nord
576	Jobcenter Vorpommern - Greifswald Süd
*/

INSERT IGNORE INTO opt_jobcenters_2024
(name,info,url,street,postcode,city,email,fon,opening_time,by_agency)
SELECT
    name,info,url,street,postcode,city,email,fon,opening_time,1
FROM jobcenters
ORDER BY postcode;
