# reservas_unp

Sistema de Reservas para la Universidad Nacional de Piura (UNP).

---

## 🔄 Flujo de Trabajo con Git (Git Workflow)

Para asegurar la colaboración fluida y mantener la rama principal (`main`) siempre funcional y libre de errores, seguimos el siguiente flujo de trabajo:

### 1. Antes de Empezar a Trabajar (Actualizar Repositorio)
Cada vez que vayas a iniciar tu jornada o a empezar una nueva tarea, es fundamental que descargues los últimos cambios que otros desarrolladores hayan subido a la rama principal:

1. Cambia a la rama principal `main`:
   ```bash
   git checkout main
   ```
2. Descarga e integra las últimas actualizaciones desde el repositorio remoto:
   ```bash
   git pull origin main
   ```

---

### 2. Crear una Rama para el Cambio/Tarea
**Nunca** trabajes directamente sobre la rama `main`. Para cada cambio, característica (feature), corrección de errores (bugfix) o limpieza, debes crear una nueva rama a partir de `main` actualizada:

1. Crea y cámbiate a la nueva rama (por ejemplo, si vas a hacer limpieza, puedes llamarla `limpieza`):
   ```bash
   git checkout -b limpieza
   ```
   *(Nota: Puedes usar cualquier nombre descriptivo para tu rama, por ejemplo: `git checkout -b feat-modulo-alumnos`)*

---

### 3. Registrar y Subir tus Cambios a Git
Una vez que hayas modificado, creado o limpiado tus archivos locales:

1. Revisa los archivos que has modificado para asegurarte de qué cambios estás guardando:
   ```bash
   git status
   ```
2. Agrega los archivos modificados al área de preparación (staging area):
   - Para agregar **todos** los archivos modificados:
     ```bash
     git add .
     ```
   - O si prefieres agregar archivos específicos uno por uno:
     ```bash
     git add ruta/al/archivo.php
     ```
3. Guarda localmente tus cambios confirmados con un mensaje descriptivo que explique qué hiciste:
   ```bash
   git commit -m "Refactorización y limpieza de controladores"
   ```
4. Sube tu rama con tus cambios locales al repositorio remoto (GitHub, GitLab, etc.):
   ```bash
   git push origin nombre-de-tu-rama
   ```
   *(En tu caso actual, si estás en `limpieza`, el comando sería: `git push origin limpieza`)*

---

### 4. Integrar los Cambios a la Rama Principal (`main`)
Una vez que tus cambios ya se encuentren en el servidor remoto dentro de tu rama (`limpieza`):

#### Opción A: A través de GitHub/GitLab (Recomendado en equipos)
1. Ve a la plataforma web de tu repositorio (por ejemplo, GitHub).
2. Verás un botón que dice **"Compare & pull request"** o similar para tu rama recién subida.
3. Crea un **Pull Request (PR)** solicitando fusionar tu rama (ej. `limpieza`) hacia `main`.
4. Una vez revisado y aprobado por el equipo o por ti mismo, presiona el botón **"Merge pull request"**.

#### Opción B: Fusión Local (Directamente desde la Consola)
Si tienes permisos directos y no se requiere revisión en la web, puedes fusionar tu rama localmente y luego subir `main`:

1. Regresa a la rama principal:
   ```bash
   git checkout main
   ```
2. Asegúrate de tener los últimos cambios (por si algún compañero subió algo mientras trabajabas):
   ```bash
   git pull origin main
   ```
3. Une (fusiona) los cambios de tu rama a `main`:
   ```bash
   git merge limpieza
   ```
4. Sube la rama `main` ya actualizada al repositorio remoto:
   ```bash
   git push origin main
   ```
5. (Opcional) Limpia tus ramas locales y remotas si ya no las vas a utilizar:
   - Eliminar rama local:
     ```bash
     git branch -d limpieza
     ```
   - Eliminar rama remota:
     ```bash
     git push origin --delete limpieza
     ```

---

> [!TIP]
> **Resumen de comandos rápidos para el día a día:**
> 1. `git checkout main` && `git pull origin main` (Actualizar antes de empezar)
> 2. `git checkout -b mi-rama-nueva` (Crear espacio de trabajo)
> 3. *Trabajar y codificar...*
> 4. `git status` (Verificar cambios)
> 5. `git add .` (Preparar cambios)
> 6. `git commit -m "mensaje"` (Guardar cambios)
> 7. `git push origin mi-rama-nueva` (Subir rama al servidor)