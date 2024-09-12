-- Retorna toda la información del registro que coincida con el email pasado por
-- parametro siempre que no haya sido afectado por un soft delete, osea que el 
-- usuario este activo

DELIMITER $$
CREATE DEFINER=`morphyx`@`%` PROCEDURE `login`(IN `param_email` TEXT)
SELECT * FROM users WHERE email = param_email AND delete_at='0000-00-00 00:00:00'$$
DELIMITER ;