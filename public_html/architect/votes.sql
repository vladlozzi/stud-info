SELECT project_id, SUM(rating_id) AS votes FROM testArchitect GROUP BY project_id ORDER BY votes DESC

