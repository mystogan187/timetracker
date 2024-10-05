
# TimeTracker Application

This is a time tracking project built with Symfony, applying the hexagonal architecture with a Domain-Driven Design (DDD) approach. It allows you to start and stop tasks, record time entries, and obtain daily summaries.

## Prerequisites

- **Docker**: Make sure you have the latest version of Docker and Docker Compose installed on your machine.

## Installation

1. **Clone the Repository**

   ```bash
   git clone https://github.com/mystogan187/timetracker.git
   cd timetracker
   ```

2. **Build and Start the Containers**

   Build Docker images and start the containers by running:

   ```bash
   docker-compose up --build
   ```

   This command will build the images and start the services defined in the `docker-compose.yml` file.

3. **Access the Application**

   Once the containers are running, you can access the web application by navigating to:

   ```
   http://localhost:8000
   ```

   Ensure that port `8000` is free and not being used by another application.

## Running Tests

The application uses PHPUnit for unit testing. To run the tests inside the Docker container, use the following command:

```bash
docker-compose exec php ./vendor/bin/phpunit tests/
```

This command will run the tests defined in the `tests/` directory.

## Project Structure

The project is organized following the principles of hexagonal architecture, separating responsibilities into different layers and contexts:

- **src/**
    - **Controller/**: Controllers that handle HTTP requests.
    - **Task/**
        - **Application/**: Application services related to tasks.
        - **Domain/**: Entities and domain logic for tasks.
        - **Infrastructure/**: Concrete implementations (repositories, persistence) for tasks.
    - **Time/**
        - **Application/**: Application services related to time entries.
        - **Domain/**: Entities and domain logic for time entries.
        - **Infrastructure/**: Concrete implementations for time entries.

## Technologies Used

- **Symfony**: PHP framework for web applications.
- **Doctrine ORM**: Object-relational mapping for database interactions.
- **PHPUnit**: Framework for unit testing in PHP.
- **Docker**: Container platform to deploy the application in isolated environments.

## Features

- **Start Task**: Start tracking time for a specific task.
- **Stop Task**: Stop the current task tracking.
- **Show Timer**: Display the timer of the ongoing task.
- **Daily Summary**: Show a summary of the time spent on tasks during the day.

## How to Use the Application

- **Home**: Visit `http://localhost:8000` to access the homepage.
- **Start a Task**: Enter the task name and click "Start".
- **Stop a Task**: Click "Stop" on the timer page.
- **View Summary**: Go to `http://localhost:8000/summary` to view the daily summary.

## Additional Notes

- **Docker Versions**: It is essential to use the latest version of Docker to ensure compatibility and take advantage of the latest features and performance improvements.
- **Port Configuration**: The default port is `8000`. If you need to change it, you can modify the `docker-compose.yml` file.
- **Data Persistence**: Data is stored in Docker volumes to maintain persistence between container restarts.
- **Database Migrations**: If you need to run migrations, you can do so with:

  ```bash
  docker-compose exec php php bin/console doctrine:migrations:migrate
  ```

## Useful Commands

- **Install Dependencies**:

  ```bash
  docker-compose exec php composer install
  ```


---

**Contact**: If you have any questions or need help, you can contact me at [aec.alexandru@gmail.com](mailto:aec.alexandru@gmail.com).

---

Thank you for using TimeTracker! I hope this tool helps you manage your time more efficiently.



# TimeTracker Application

Este es un proyecto de seguimiento de tiempo construido con Symfony, aplicando la arquitectura hexagonal con un enfoque de Diseño Orientado al Dominio (DDD). Permite iniciar y detener tareas, registrar entradas de tiempo y obtener resúmenes diarios.

## Requisitos Previos

- **Docker**: Asegúrate de tener instalada la versión más reciente de Docker y Docker Compose en tu máquina.

## Instalación

1. **Clonar el Repositorio**

   ```bash
   git clone https://github.com/mystogan187/timetracker.git
   cd timetracker
   ```

2. **Construir y Levantar los Contenedores**

   Construye las imágenes de Docker y levanta los contenedores ejecutando:

   ```bash
   docker-compose up --build
   ```

   Este comando construirá las imágenes y levantará los servicios definidos en el archivo `docker-compose.yml`.

3. **Acceder a la Aplicación**

   Una vez que los contenedores estén en funcionamiento, puedes acceder a la aplicación web navegando a:

   ```
   http://localhost:8000
   ```

   Asegúrate de que el puerto `8000` esté libre y no utilizado por otra aplicación.

## Ejecución de Tests

La aplicación utiliza PHPUnit para las pruebas unitarias. Para ejecutar los tests dentro del contenedor Docker, utiliza el siguiente comando:

```bash
docker-compose exec php ./vendor/bin/phpunit tests/
```

Este comando ejecutará las pruebas definidas en el directorio `tests/`.

## Estructura del Proyecto

El proyecto está organizado siguiendo los principios de la arquitectura hexagonal, separando las responsabilidades en diferentes capas y contextos:

- **src/**
    - **Controller/**: Controladores que manejan las solicitudes HTTP.
    - **Task/**
        - **Application/**: Servicios de aplicación relacionados con tareas.
        - **Domain/**: Entidades y lógica de dominio para tareas.
        - **Infrastructure/**: Implementaciones concretas (repositorios, persistencia) para tareas.
    - **Time/**
        - **Application/**: Servicios de aplicación relacionados con entradas de tiempo.
        - **Domain/**: Entidades y lógica de dominio para entradas de tiempo.
        - **Infrastructure/**: Implementaciones concretas para entradas de tiempo.

## Tecnologías Utilizadas

- **Symfony**: Framework de PHP para aplicaciones web.
- **Doctrine ORM**: Mapeo objeto-relacional para interacciones con la base de datos.
- **PHPUnit**: Framework para pruebas unitarias en PHP.
- **Docker**: Plataforma de contenedores para desplegar la aplicación en entornos aislados.

## Funcionalidades

- **Iniciar Tarea**: Comienza a rastrear el tiempo de una tarea específica.
- **Detener Tarea**: Detiene el seguimiento de la tarea actual.
- **Mostrar Temporizador**: Muestra el temporizador de la tarea en curso.
- **Resumen Diario**: Muestra un resumen del tiempo invertido en tareas durante el día.

## Uso de la Aplicación

- **Inicio**: Visita `http://localhost:8000` para acceder a la página principal.
- **Iniciar una Tarea**: Ingresa el nombre de la tarea y presiona "Iniciar".
- **Detener una Tarea**: Presiona "Detener" en la página del temporizador.
- **Ver Resumen**: Navega a `http://localhost:8000/summary` para ver el resumen diario.

## Notas Adicionales

- **Versiones de Docker**: Es fundamental usar la versión más reciente de Docker para asegurar la compatibilidad y aprovechar las últimas características y mejoras de rendimiento.
- **Configuración de Puertos**: El puerto por defecto es el `8000`. Si necesitas cambiarlo, puedes modificar el archivo `docker-compose.yml`.
- **Persistencia de Datos**: Los datos se almacenan en volúmenes de Docker para mantener la persistencia entre reinicios de contenedores.
- **Migraciones de Base de Datos**: Si necesitas ejecutar migraciones, puedes hacerlo con:

  ```bash
  docker-compose exec php php bin/console doctrine:migrations:migrate
  ```

## Ejecución de Comandos Útiles

- **Instalar Dependencias**:

  ```bash
  docker-compose exec php composer install
  ```


---

**Contacto**: Si tienes alguna pregunta o necesitas ayuda, puedes contactarme en [aec.alexandru@gmail.com](mailto:aec.alexandru@gmail.com).

---

¡Gracias por utilizar TimeTracker! Espero que esta herramienta te ayude a gestionar tu tiempo de manera más eficiente.
