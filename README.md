# Endpoints

| *Endpoint*       | *Método* | *Descripción*                                                                | *Query Params* (si aplica)                                                                                                                                              | *Campos (Cuerpo de la solicitud)*                                                                                                                                                          |
|---------------------|------------|--------------------------------------------------------------------------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| /api/autores | GET | Obtiene una colección de autores con opciones de paginación, filtrado y orden. | - **paginate**: 1 o 0 (default: 0).<br>- **page_number**: Número de página (default: 1).<br>- **page_size**: Elementos por página (default: 50).<br>- **filter_field**: Campo para filtrar (ej: autor.nombre).<br>- **filter**: Valor a filtrar.<br>- **sort_by**: Campo para ordenar (ej: autor_nombre).<br>- **order**: Dirección (ASC o DES, default: ASC). | N/A |
| /api/autores/:ID  | GET | Obtiene los detalles de un autor específico mediante su ID. | N/A | N/A |
| /api/autores/:ID  | PUT | Actualiza los datos de un autor específico mediante su ID. | N/A | - **nombre**: Nombre del autor (obligatorio).<br>- **biografia**: Biografía del autor (obligatorio).<br>- **imagen**: URL o datos de la imagen del autor (obligatorio).|
| /api/autores | POST | Crea un nuevo autor con los datos proporcionados en la solicitud. | N/A | - **nombre**: Nombre del autor (obligatorio).<br>- **biografia**: Biografía del autor (obligatorio).<br>- **imagen**: URL o datos de la imagen del autor (obligatorio). |
| /api/autores/:ID | DELETE | Elimina un autor específico mediante su ID. | N/A | N/A |
| /api/libros | GET | Obtiene una colección de libros con opciones de paginación, filtrado y orden. | - **paginate**: 1 o 0 (default: 1).<br>- **page_number**: Número de página (default: 0).<br>- **page_size**: Elementos por página (default: 50).<br>- **filter_field**: Campo para filtrar (ej: libro.titulo).<br>- **filter**: Valor a filtrar.<br>- **sort_by**: Campo para ordenar (ej: titulo).<br>- **order**: Dirección (ASC o DES, default: ASC). | N/A |
| /api/libros/:ID   | GET | Obtiene los detalles de un libro específico mediante su ID. | N/A | N/A |
| /api/libros/:ID   | PUT | Actualiza los datos de un libro específico mediante su ID. | N/A | - **isbn**: Código ISBN del libro (obligatorio).<br>- **titulo**: Título del libro (obligatorio).<br>- **fecha_de_publicacion**: Fecha de publicación (obligatorio).<br>- **editorial**: Editorial del libro (obligatorio).<br>- **encuadernado**: Tipo de encuadernación (obligatorio).<br>- **sinopsis**: Descripción del libro (obligatorio).<br>- **autor**: ID del autor asociado (obligatorio).<br>- **nro_de_paginas**: Número de páginas (obligatorio).<br>- **old_isbn**: ISBN anterior (obligatorio). |
| /api/libros | POST | Crea un nuevo libro con los datos proporcionados en la solicitud. | N/A | - **isbn**: Código ISBN del libro (obligatorio).<br>- **titulo**: Título del libro (obligatorio).<br>- **fecha_de_publicacion**: Fecha de publicación (obligatorio).<br>- **editorial**: Editorial del libro (obligatorio).<br>- **encuadernado**: Tipo de encuadernación (obligatorio).<br>- **sinopsis**: Descripción del libro (obligatorio).<br>- **autor**: ID del autor asociado (obligatorio).<br>- **nro_de_paginas**: Número de páginas (obligatorio). |
| /api/libros/:ID   | DELETE     | Elimina un libro específico mediante su ID. | N/A | N/A |
| /api/login        | POST       | Realiza la autenticación de usuario y devuelve un token JWT.                 | N/A                                                                                                                                                                      | - **username**: Nombre de usuario del usuario (obligatorio).<br>- **password**: Contraseña del usuario (obligatorio).                                                                         |


### Campos explicados:

#### **Valores permitidos en los Query Params**
- **En ordenar libro**: isbn, titulo, fecha_de_publicacion, editorial, encuadernado, sinopsis, autor, nro_de_paginas, autor_id, autor_biografia, autor_imagen
    - **Tipos de orden utilizados**: ASC, DESC
- **En ordenar autor**: id,nombre, biografia, imagen
    - **Tipos de orden utilizados**: ASC, DESC
- **En filtrar libro**: isbn, titulo, fecha_de_publicacion, editorial, encuadernado, sinopsis, autor, nro_de_paginas, autor.id, autor.biografia, autor.imagen
- **En filtrar autor**: id,nombre, biografia, imagen
    

#### *Libros*
- **isbn**: Código ISBN del libro (obligatorio).  
- **titulo**: Título del libro (obligatorio).  
- **fecha_de_publicacion**: Fecha en que se publicó el libro (obligatorio).  
- **editorial**: Editorial que publicó el libro (obligatorio).  
- **encuadernado**: Tipo de encuadernación (ejemplo: "Tapa dura", "Tapa blanda", obligatorio).  
- **sinopsis**: Resumen o descripción del contenido del libro (obligatorio).  
- **autor**: ID del autor asociado al libro (obligatorio).  
- **nro_de_paginas**: Número de páginas del libro (obligatorio).  
- **old_isbn**: Código ISBN anterior, utilizado en actualizaciones (obligatorio).  

#### **Autores**
- **nombre**: Nombre completo del autor (obligatorio).  
- **biografia**: Descripción o biografía del autor (obligatorio).  
- **imagen**: URL o datos de la imagen del autor (obligatorio).  

#### **Login**
- **username**: Nombre de usuario del usuario (obligatorio).  
- **password**: Contraseña del usuario (obligatorio).

#### **Datos de Login**
- **username**: webadmin.  
- **password**: admin.
