Usuario para ingresar al sismtema:

++Administrador (rol administrador):

-Nombre de usuario: admin
-Contraseña: 12345678

++Equipo de guardia (rol equipo de guardia):

-Nombre de usuario: equipodeguardia
-Contraseña: 12345678

++Super administrador (rol equipo de guardia y rol administrador)

-Nombre de usuario: superadmin
-Contraseña: 12345678

 -----------------------------------Aclaraciones y Cambios -----------------------------------

+ Se añadio la columna "admin" en la tabla "permiso": esto es para mejorar la eficiencia en la carga dinamica de las vistas, indica si el permiso pertenece al dropdown "administracion" (agrupamiento)

+ Se añadio la columna "partido" en la tabla "paciente": si bien con la localidad se deduce el partido, cuando se da de alta un paciente, la localidad y el partido no son obligatorios.

+ Algunas claves foraneas en la tabla "paciente" fueron cambiadas para que puedan tener el valor null: esto es para poder permitir la carga de pacientes "NN".


 
link api intituciones:https://grupo12.proyecto2018.linti.unlp.edu.ar/apiRest/api.php/instituciones

nombre bot de telegram: grupo12_bot

en el comando /instituciones_region_sanitaria:region_sanitaria se espera que region_sanitaria sea el nombre de la region(numero romano de 1 a 12)
ejemplo: /instituciones_region_sanitaria:VI
