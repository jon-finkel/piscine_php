SELECT *
         FROM distrib
         WHERE name LIKE '%y%y%'
               || id_distrib IN (42, 71, 88, 89, 90)
               || id_distrib BETWEEN 62 AND 69
         LIMIT 5
         OFFSET 2;
