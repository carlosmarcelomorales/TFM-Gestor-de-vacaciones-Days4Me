# ⛱ TFM Gestor de vacaciones Days4Me

## Índice

* [🎉 Introducción](#-introduccin)
* [🐳 Puesta en marcha](#-inicializacion)
* [📊 Infraestructura](#-infraestructura)
* [🎯 Módulos](#-modulos)
    * [🔗 Compañía](#-companies)
    * [🏪 Usuarios](#-users)
    * [🎰 Centros de trabajo](#-workplaces)
    * [🔗 Departamentos](#-departments)
    * [💻 Puestros de trabajo](#-workpositions)
    * [🌍 Días festivos](#-holidays)
    * [🤔 Solicitudes](#-requests)
    * [🎰 Calendario](#-calendario)
* [👀 Acceso & Mapa Web](#mapa-web) 
* [🚀 Arquitectura](#-arquitectura)
* [✅ Test & API Rest](#testing)
* [🤔 Consideraciones](#-consideraciones)

## 🎉 Introducción

Este proyecto surge de la necesidad que tienen las empresas al gestionar las solicitudes y vacaciones de sus empleados cuya 
finalidad es facilitar y agilizar su gestión. Este proyecto está realizado en PHP, con Jquery y con motor de base de datos 
MySql teniendo como base el framework de Symfony. Además de algunas dependencias de Symfony, más info en [composer](composer.json)

## 🔗 Puesta en marcha

 Previamente se ha de tener instalado [Docker](https://www.docker.com/get-started)
  
 El repositorio cuenta con los siguientes servicios:
	 
   *  Mysql: 3306
   *  Nginx: 8080
   *  Php: 9000


En el proyecto se incluye un fichero Makefile con comandos para la instalación y gestión del contenedor:

    deploy:
       * Ejecuta todos las instrucciones necesarias para iniciar los servicios del contenedor en entorno de producción
    start:                       
       * Inicia el contenedor 
    stop:                 
       * Para el contenedor
    build:                     
       * Construye los servicios del contenedor
    remove:                      
       * Para todos los servicios del contenedor y los elimina
    logs:                        
       * Muestra todos los logs de los contenedores 
    logs@php:
       * Muestra los logs del contenedor de PHP
    composer:
       * Instala las dependencias de composer
    interactive:                 
       * Ejecuta el contenedor en modo shell

-Primeros pasos para la ejecución del proyecto:   

            - Clonar el proyecto.
             
            - Ir al directorio raíz del proyecto.
            
            - Ejecutar `make deploy` (Para entorno de producción)           
                    
            👀 Debido a la instalación de dependencias, la primera vez que se ejecute el contenedor puede demorarse más de lo normal.

            - Acceder mediante la url http://localhost:8080

## 📊 Infraestructura

Los ficheros de configuración de los contenedores y del modelo de datos, se encuentran en:
 
 * Directorios ops y etc
 
    ```scala          
    ├── ops
    │   ├── database
    │   │   ├── holidays.sql
    │   │   └── holidaysDev.sql 
     └── php-fpm
       ├── Dockerfile
       └── opcache.ini

   etc
      └── nginx
          └── default.conf
      
    ```
 
 *  Makefile y Docker-compose
    ```scala          
    src
    ├── Makefile    
    └── docker-compose.yml
 
     ``` 
   
## 🎯 Módulos 

### 🔗 Compañía
El caso de uso [CreateCompany](src/Company/Application/Create/CreateCompany.php) espera los datos del 
[CompanyController](src/Company/Infrastructure/Controller/CompanyController.php) para poder tratar toda la información 
del agregado [Company](src/Company/Domain/Model/Aggregate/Company.php) y así guardarla mediante el método 
[save del repositorio](src/Company/Domain/CompanyRepository.php). 
Después se dispara el evento [CompanySavedDomainEvent](src/Company/Domain/Model/Event/CompanySavedDomainEvent.php) que 
es el encargado de tratar toda información de la estructura de una compañía, mediante el subscriptor [CreateStructureOnCompanySaved](src/Company/Infrastructure/Framework/EventSubscriber/CreateStructureOnCompanySaved.php) donde se llamara a los casos de usos de [WorkPlace](src/WorkPlace/Application/Create/CreateWorkPlace.php), 
[Department](src/Department/Application/Create/CreateDepartment.php) y [WorkPosition](src/WorkPosition/Application/Create/CreateWorkPosition.php) 
para que guarden la información correspondiente.
 
### 🏪 Usuarios
El caso de uso [CreateUser](src/User/Application/Create/CreateUser.php) espera los datos del 
[UserController](src/User/Infrastructure/Controller/UserController.php) para poder tratar la información del agregado 
[User](src/User/Domain/Model/Aggregate/User.php) y asignarle el rol correspondiente mediante [GetRoleById](src/Role/Application/Search) y así 
guardarla mediante el método [save del repositorio](src/User/Domain/UserRepository.php).
Después se dispara el evento [UserCreatedDomainEvent](src/User/Domain/Model/Event/UserCreatedDomainEvent.php) que es el 
encargado de procesar la información para el envío del mail de bienvenida al usuario y el token para que informe su 
contraseña, mediante el subscriptor [RecoveryTokenOnUserCreated](src/User/Infrastructure/Framework/EventSubscriber) 
donde se llamara al caso de uso [RequestToken](src/User/Application/Security/RequestToken.php) que genera un token para 
que el usuario informe de su contraseña y disparar el evento [RequestTokenDomainEvent](src/User/Domain/Model/Event/RequestTokenDomainEvent.php) 
encargado de llamar al caso de uso [SendMailRecoveryTokenOnUserSaved](src/Mail/Infrastructure/Framework/EventSubscriber/SendMailRecoveryTokenOnUserSaved.php) 
que envía el mail de bienvenida al usuario.

### 🎰 Centros de trabajo
El caso de uso [CreateWorkPlace](src/WorkPlace/Application/Create/CreateWorkPlace.php) espera los datos del 
[WorkPlaceController](src/WorkPlace/Infrastructure/Controller/WorkPlaceController.php) para poder tratar la información 
del agregado [WorkPlace](src/WorkPlace/Domain/Model/Aggregate/WorkPlace.php) y así guardarla mediante el método 
[save del repositorio](src/WorkPlace/Domain/WorkPlaceRepository.php). Para consultar los workplace de una compañía desde el [WorkPlaceController](src/WorkPlace/Infrastructure/Controller/WorkPlaceController.php)
se llama al caso de uso [GetWorkPlaces](src/WorkPlace/Application/Get/GetWorkPlaces.php) que nos listará todos los workplaces 
y para actualizar algún dato relacionado con un workplace, se llama al caso de uso [UpdateWorkPlace](src/WorkPlace/Application/Update/UpdateWorkPlace.php) 
desde el mismo controlador.

### 🔗 Departamentos
El caso de uso [CreateDepartment](src/Department/Application/Create/CreateDepartment.php) espera los datos del 
[DepartmentController](src/Department/Infrastructure/Controller/DepartmentController.php) para poder tratar la información 
del agregado [Department](src/Department/Domain/Model/Aggregate/Department.php) y así guardarla mediante el método [save del repositorio](src/Department/Domain/DepartmentRepository.php). 
Para consultar los departments de un workplace desde el [DepartmentController](src/Department/Infrastructure/Controller/DepartmentController.php) 
se llama al caso de uso [GetDepartments](src/Department/Application/Get/GetDepartments.php) que nos listará los departments asociados a un workplace y 
para actualizar algún dato relacionado con un department, se llama al caso de uso [UpdateDepartment](src/Department/Application/Update/UpdateDepartment.php) 
desde el mismo controlador.

### 💻 Puestros de trabajo
Para gestionar los puestos de trabajo de cada empresa, usamos el [WorkPositionController](src/WorkPosition/Infrastructure/Controller/WorkPositionController.php).
Desde el punto de entrada de la función de invoke llamamos al caso de uso de [GetWorkPositions](src/WorkPosition/Application/Get/GetWorkPositions.php) 
para obtener el listado de puestos de trabajo.

Para añadir nuevos puestos, tenemos la función addWorkPosition que crea un formulario con el [CreateWorkPositionType](src/WorkPosition/Infrastructure/Form/CreateWorkPositionType.php).
Al validar el formulario se llama la función de saveWorkPosition con el caso de uso de [CreateWorkPositionRequest](src/WorkPosition/Application/Create/CreateWorkPosition.php).

De la mmisma manera, podemos editar. Al darla a enviar el formulario. se llama a la función updateWorkPosition, con el caso de uso de [UpdateWorkPosition](src/WorkPosition/Application/Update/UpdateWorkPosition.php).

### 🌍 Días festivos
Para gestionar los dias festivos de las empresas, el punto de entrada es el [HolidayController](src/Holiday/Infrastructure/Controller/HolidayController.php).
Desde la función de invoke, se llama al caso de uso de [GetHolidays](src/Holiday/Application/Get/GetHolidays.php) para obtener 
el listado de dias festivos.

Para añadir nuevos dias festivos, se llama a la función addHoliday, donde pintará un formulario creado con el 
[CreateHolidayType](src/Holiday/Infrastructure/Form/CreateHolidayType.php). Al rellenar los datos del formulario, se llama
a la función saveNewHoliday, donde se acaba llamando al caso de uso de [CreateHoliday](src/Holiday/Application/Create/CreateHoliday.php).
De la misma manera, para la funcionalidad de editar se llama a la función editHoliday del mismo controlador. Y una vez 
se ha hecho submit del formulario, se llama al updateHoliday donde se llama el caso de uso de [UpdateHoliday](src/Holiday/Application/Update/UpdateHoliday.php)
 
### 🤔 Solicitudes

Las solicitudes es la parte mas importante de nuestra aplicación. Para gestionarlo, entramos en el [RequestController](src/Request/Infrastructure) 
y en la función de invoke, donde llamamos al [GetRequests](src/Request/Application/Get/GetRequests.php) para obtener las
solicitudes que puede ver el usuario. 

Tenemos la función addRequests que acaba generando un formulario con el [CreateRequestType](src/Request/Infrastructure/Form/CreateRequestType.php).
Al rellenarlo y validarlo, se llama la función de saveNewRequest donde aplica el caso de uso de [CreateRequest](src/Request/Application/Create/CreateRequest.php)

Para la edición de las solicitudes, se llama la función de editRequest, donde al validarla acaba llamando a la función 
de updateRequest, donde usa el caso de uso de [UpdateRequests](src/Request/Application/Update/UpdateRequest.php)
para acabar actualizando los datos introducidos.


### 🎰 Calendario
Para el caso del calendario no tenemos un caso de uso ni dominio en particular, ya que se nutre de otros modulos. 
Empezamos llamando al [CalendarController](src/Calendar/Infrastructure/Controller/CalendarController.php) desde el que 
llamaremos a varios casos de uso para poder obtener los filtros y datos necesarios. Primero, obtenemos los usuarios a 
partir de los cuales vamos a poder filtrar en el calendario. Para ello podemos llamar a dos casos de uso distintos, en 
función de los permisos del usuario. Si es un usuario básico o un encargado de la empresa, llamaremos al caso de uso de 
[GetUsers](src/User/Application/Search/GetUser.php). Para el resto de usuarios, llamaremos a [GetUsersCompany](src/User/Application/Search/GetUsersByCompany.php).

Una vez obtenido el listado de usuarios, se usa este mismo para conseguir sus solilcitudes para poder mostrarlas todas en 
el calendario. Para ello se llama a [GetRequestsByMultipleUsers](src/Request/Application/Get/GetRequestsByMultipleUsers.php)
Para obtener los eventos que se acaban mostrando en el calendario a traves de las requests, llamamos al 
[GetEventsCalendar](src/Request/Application/Get/GetEventsCalendar.php).

Una vez tenemos los datos para mostrar en el calendario, obtenemos los filtros por tipos de solicitud y por departamento. 
Para ello se llama a los casos de uso de [GetAllTypesRequests](src/TypeRequest/Application/Search/GetAllTypeRequests.php) y
[GetDepartmentsRequests](src/Department/Application/Get/GetDepartments.php).


## 👀 Acceso & Mapa Web

Una vez tenemos el entorno disponible y queremos ver en funcionamiento el proyecto, cabe mencionar que por defecto se 
cargan unos datos de inicio en la base de datos. Detalle de la información:

 * Países, regiones y códigos postales
 * Estado y tipos de solicitudes
 * Tipo de roles

Hemos pensado que para hacer más fácil la accesibilidad a la aplicación, hemos creado por defecto el SUPER USUARIO con 
la opción de personalización de la cuenta de correo, datos de la cuenta:  
 
 * Usuario: days4me@days4me.com
 * Contraseña: Days4Me

Una vez logeado con el usuario days4me desde el profile está disponible la opción de personalizar la cuenta de correo 
con una propia, de tal manera que la próxima vez que se quieran logear como SUPER USUARIO se ha de hacer con la cuenta 
personalizada.

Estos son los diferentes enlaces que hay definidos en la aplicación:  

 * 🌍 Login  [http://localhost:8080/login](http://localhost:8080/login) 
 * 🌍 Register [http://localhost:8080/register](http://localhost:8080/register)
 * 🌍 Recuperar contraseña [http://localhost:8080/password/recovery](http://localhost:8080/password/recovery)
 * 🌍 Documentación Apis [http://localhost:8080/doc/api](http://localhost:8080/doc/api)
 * 🌍 Apis [http://localhost:8080/api/{nombre modulo}](http://localhost:8080/api/companies)
 
 
## 🚀 Arquitectura
Esta practica sigue el patrón de Arquitectura Hexagonal, para ello se ha estructurado de la siguiente  manera:

```scala
$ tree

├── src
│   ├── Calendar
│   │   └── Infrastructure
│   │       └── Controller
│   │           └── CalendarController.php
│   ├── Company
│   │   ├── Application
│   │   │   ├── Api
│   │   │   │   ├── CompanyResponse.php
│   │   │   │   ├── GetApiCompanies.php
│   │   │   │   └── GetApiCompaniesRequest.php
│   │   │   ├── Create
│   │   │   │   ├── CreateCompany.php
│   │   │   │   └── CreateCompanyRequest.php
│   │   │   ├── Get
│   │   │   │   ├── GetCompanies.php
│   │   │   │   ├── GetCompaniesRequest.php
│   │   │   │   ├── GetCompany.php
│   │   │   │   └── GetCompanyRequest.php
│   │   │   └── Update
│   │   │       ├── UpdateCompany.php
│   │   │       └── UpdateCompanyRequest.php
│   │   ├── Domain
│   │   │   ├── CompanyRepository.php
│   │   │   ├── Exception
│   │   │   │   ├── CompanyAlreadyExistsException.php
│   │   │   │   ├── CompanyErrorToCreateException.php
│   │   │   │   ├── CompanyIdNotExistsException.php
│   │   │   │   ├── CompanyServiceIsNotAvailableException.php
│   │   │   │   └── CompanyVatNotExistsException.php
│   │   │   └── Model
│   │   │       ├── Aggregate
│   │   │       │   └── Company.php
│   │   │       └── Event
│   │   │           └── CompanySavedDomainEvent.php
│   │   └── Infrastructure
│   │       ├── Controller
│   │       │   ├── Api
│   │       │   │   └── ApiCompanyController.php
│   │       │   └── CompanyController.php
│   │       ├── Form
│   │       │   └── CompanyType.php
│   │       ├── Framework
│   │       │   └── EventSubscriber
│   │       │       └── CreateStructureOnCompanySaved.php
│   │       └── Persistence
│   │           └── Doctrine
│   │               ├── Mapping
│   │               │   └── Company.orm.yml
│   │               └── Repository
│   │                   └── DoctrineCompanyRepository.php
│   ├── Country
│   │   ├── Application
│   │   │   ├── Country
│   │   │   │   ├── CountryResponse.php
│   │   │   │   ├── GetCountries.php
│   │   │   │   ├── GetCountriesRequest.php
│   │   │   │   ├── GetCountry.php
│   │   │   │   └── GetCountryRequest.php
│   │   │   ├── PostalCode
│   │   │   │   ├── GetPostalCode.php
│   │   │   │   ├── GetPostalCodeRequest.php
│   │   │   │   ├── GetPostalCodes.php
│   │   │   │   ├── GetPostalCodesRequest.php
│   │   │   │   └── PostalCodeResponse.php
│   │   │   ├── Region
│   │   │   │   ├── GetRegion.php
│   │   │   │   ├── GetRegionRequest.php
│   │   │   │   ├── GetRegions.php
│   │   │   │   ├── GetRegionsRequest.php
│   │   │   │   └── RegionResponse.php
│   │   │   └── Town
│   │   │       ├── GetTown.php
│   │   │       ├── GetTownRequest.php
│   │   │       ├── Search
│   │   │       │   ├── GetTowns.php
│   │   │       │   ├── GetTownsRequest.php
│   │   │       │   ├── SearchTown.php
│   │   │       │   └── SearchTownRequest.php
│   │   │       ├── TownResponse.php
│   │   │       ├── ViewTowns.php
│   │   │       └── ViewTownsRequest.php
│   │   ├── Domain
│   │   │   ├── CountryRepository.php
│   │   │   ├── Exception
│   │   │   │   ├── CountryServiceIsNotAvailableException.php
│   │   │   │   ├── PostalCodeNotExistsException.php
│   │   │   │   ├── PostalCodeServiceIsNotAvailableException.php
│   │   │   │   ├── RegionServiceIsNotAvailableException.php
│   │   │   │   └── TownServiceIsNotAvailableException.php
│   │   │   ├── Model
│   │   │   │   └── Aggregate
│   │   │   │       ├── Country.php
│   │   │   │       └── Region
│   │   │   │           ├── PostalCode
│   │   │   │           │   └── PostalCode.php
│   │   │   │           ├── Region.php
│   │   │   │           └── Town
│   │   │   │               └── Town.php
│   │   │   ├── PostalCodeRepository.php
│   │   │   ├── RegionRepository.php
│   │   │   └── TownRepository.php
│   │   └── Infrastructure
│   │       ├── Controller
│   │       │   └── CountryController.php
│   │       ├── Persistence
│   │       │   └── Doctrine
│   │       │       └── Mapping
│   │       │           ├── Country.orm.yml
│   │       │           ├── Region.PostalCode.PostalCode.orm.yml
│   │       │           ├── Region.Region.orm.yml
│   │       │           └── Region.Town.Town.orm.yml
│   │       └── Repository
│   │           ├── DoctrineCountryRepository.php
│   │           ├── DoctrinePostalCodeRepository.php
│   │           ├── DoctrineRegionRepository.php
│   │           └── DoctrineTownRepository.php
│   ├── Dashboard
│   │   └── Infrastructure
│   │       └── Controller
│   │           └── DashboardController.php
│   ├── Department
│   │   ├── Application
│   │   │   ├── Api
│   │   │   │   ├── DepartmentResponse.php
│   │   │   │   ├── GetApiDepartments.php
│   │   │   │   └── GetApiDepartmentsRequest.php
│   │   │   ├── Create
│   │   │   │   ├── CreateDepartment.php
│   │   │   │   └── CreateDepartmentRequest.php
│   │   │   ├── Find
│   │   │   │   ├── FindDepartment.php
│   │   │   │   └── FindDepartmentRequest.php
│   │   │   ├── Get
│   │   │   │   ├── GetDepartments.php
│   │   │   │   └── GetDepartmentsRequest.php
│   │   │   ├── Search
│   │   │   │   └── GetDepartmentByWorkplace.php
│   │   │   └── Update
│   │   │       ├── UpdateDepartment.php
│   │   │       └── UpdateDepartmentRequest.php
│   │   ├── Domain
│   │   │   ├── DepartmentRepository.php
│   │   │   ├── Exception
│   │   │   │   ├── DepartmentAlreadyExistsException.php
│   │   │   │   ├── DepartmentIdNotExistsException.php
│   │   │   │   ├── DepartmentNotBlockedException.php
│   │   │   │   └── DepartmentServiceIsNotAvailableException.php
│   │   │   └── Model
│   │   │       └── Aggregate
│   │   │           └── Department.php
│   │   └── Infrastructure
│   │       ├── Controller
│   │       │   ├── Api
│   │       │   │   └── ApiDepartmentController.php
│   │       │   └── DepartmentController.php
│   │       ├── Form
│   │       │   └── CreateDepartmentType.php
│   │       ├── Persistence
│   │       │   └── Doctrine
│   │       │       └── Mapping
│   │       │           └── Department.orm.yml
│   │       └── Repository
│   │           └── DepartmentDoctrineRepository.php
│   ├── Document
│   │   ├── Application
│   │   │   ├── Api
│   │   │   │   ├── DocumentResponse.php
│   │   │   │   ├── GetApiDocuments.php
│   │   │   │   └── GetApiDocumentsRequest.php
│   │   │   ├── Save
│   │   │   │   ├── SaveDocument.php
│   │   │   │   └── SaveDocumentRequest.php
│   │   │   └── Upload
│   │   │       ├── UploadDocument.php
│   │   │       └── UploadDocumentRequest.php
│   │   ├── Domain
│   │   │   ├── DocumentRepository.php
│   │   │   ├── Exception
│   │   │   │   ├── DocumentErrorToUploadException.php
│   │   │   │   ├── DocumentIsNotValid.php
│   │   │   │   └── DocumentServiceIsNotAvailableException.php
│   │   │   └── Model
│   │   │       ├── Aggregate
│   │   │       │   └── Document.php
│   │   │       └── ValueObject
│   │   │           └── newDocument.php
│   │   └── Infrastructure
│   │       ├── Controller
│   │       │   └── Api
│   │       │       └── ApiDocumentController.php
│   │       ├── Persistence
│   │       │   └── Doctrine
│   │       │       └── Mapping
│   │       │           └── Document.orm.yml
│   │       └── Repository
│   │           └── DocumentDoctrineRepository.php
│   ├── HelloWorld
│   │   ├── Application
│   │   │   ├── HelloWordRequest.php
│   │   │   ├── HelloWorld.php
│   │   │   └── HelloWorldResponse.php
│   │   └── GetHelloWorldController.php
│   ├── Holiday
│   │   ├── Application
│   │   │   ├── Api
│   │   │   │   ├── GetApiHolidays.php
│   │   │   │   ├── GetApiHolidaysRequest.php
│   │   │   │   └── HolidaysResponse.php
│   │   │   ├── Create
│   │   │   │   ├── CreateHoliday.php
│   │   │   │   └── CreateHolidayRequest.php
│   │   │   ├── Get
│   │   │   │   ├── GetHoliday.php
│   │   │   │   ├── GetHolidayRequest.php
│   │   │   │   ├── GetHolidays.php
│   │   │   │   ├── GetHolidaysByRange.php
│   │   │   │   ├── GetHolidaysByRangeRequest.php
│   │   │   │   └── GetHolidaysRequest.php
│   │   │   └── Update
│   │   │       ├── UpdateHoliday.php
│   │   │       └── UpdateHolidayRequest.php
│   │   ├── Domain
│   │   │   ├── Exception
│   │   │   │   ├── HolidayAlreadyExistsException.php
│   │   │   │   ├── HolidayIdNotExistsException.php
│   │   │   │   └── HolidayServiceIsNotAvailableException.php
│   │   │   ├── HolidayRepository.php
│   │   │   └── Model
│   │   │       └── Aggregate
│   │   │           └── Holiday.php
│   │   └── Infrastructure
│   │       ├── Controller
│   │       │   ├── Api
│   │       │   │   └── ApiHolidayController.php
│   │       │   └── HolidayController.php
│   │       ├── Form
│   │       │   └── CreateHolidayType.php
│   │       ├── Persistence
│   │       │   └── Doctrine
│   │       │       └── Mapping
│   │       │           └── Holiday.orm.yml
│   │       └── Repository
│   │           └── HolidayDoctrineRepository.php
│   ├── Kernel.php
│   ├── Mail
│   │   ├── Application
│   │   │   ├── SendNewRequestEmail.php
│   │   │   └── SendRecoveryPasswordEmail.php
│   │   ├── Domain
│   │   │   └── Model
│   │   │       ├── Event
│   │   │       │   └── WelcomeEmailSentDomainEvent.php
│   │   │       └── Service
│   │   │           └── SendEmailService.php
│   │   └── Infrastructure
│   │       ├── Framework
│   │       │   └── EventSubscriber
│   │       │       ├── SendMailNewRequestOnAvailableDays.php
│   │       │       ├── SendMailRecoveryTokenOnUserSaved.php
│   │       │       └── SendWelcomeEmailOnUserSaved.php
│   │       └── Service
│   │           └── SendWelcomeCompanyEmail.php
│   ├── Menu
│   │   └── Infrastructure
│   │       └── Framework
│   │           └── Factory
│   │               └── MenuBuilder.php
│   ├── Request
│   │   ├── Application
│   │   │   ├── Api
│   │   │   │   ├── GetApiRequests.php
│   │   │   │   ├── GetApiRequestsRequest.php
│   │   │   │   └── RequestResponse.php
│   │   │   ├── Calculate
│   │   │   │   ├── CalculateAvailableDays.php
│   │   │   │   ├── CalculateAvailableDaysRequest.php
│   │   │   │   ├── CalculateAvailableDaysResponse.php
│   │   │   │   ├── CalculateRequestsByStatus.php
│   │   │   │   └── CalculateRequestsByStatusRequest.php
│   │   │   ├── Create
│   │   │   │   ├── CreateRequest.php
│   │   │   │   └── CreateRequestRequest.php
│   │   │   ├── Find
│   │   │   │   ├── FindRequest.php
│   │   │   │   └── FindRequestRequest.php
│   │   │   ├── Get
│   │   │   │   ├── GetEventsCalendar.php
│   │   │   │   ├── GetEventsCalendarRequest.php
│   │   │   │   ├── GetEventsCalendarResponse.php
│   │   │   │   ├── GetRequest.php
│   │   │   │   ├── GetRequestByUser.php
│   │   │   │   ├── GetRequestByUserRequest.php
│   │   │   │   ├── GetRequestRequest.php
│   │   │   │   ├── GetRequests.php
│   │   │   │   ├── GetRequestsByMultipleUsers.php
│   │   │   │   ├── GetRequestsByMultipleUsersRequest.php
│   │   │   │   ├── GetRequestsByRange.php
│   │   │   │   ├── GetRequestsByRangeRequest.php
│   │   │   │   └── GetRequestsRequest.php
│   │   │   └── Update
│   │   │       ├── UpdateRequest.php
│   │   │       ├── UpdateRequestRequest.php
│   │   │       ├── UpdateStatusRequest.php
│   │   │       └── UpdateStatusRequestRequest.php
│   │   ├── Domain
│   │   │   ├── Exception
│   │   │   │   ├── DaysCoincideWithOtherRequestsException.php
│   │   │   │   ├── RequestAlreadyExistsException.php
│   │   │   │   ├── RequestIdNotExistsException.php
│   │   │   │   ├── RequestNotAvailableDaysException.php
│   │   │   │   └── RequestServiceIsNotAvailableException.php
│   │   │   ├── Model
│   │   │   │   ├── Aggregate
│   │   │   │   │   └── Request.php
│   │   │   │   └── Event
│   │   │   │       ├── CreateRequestDomainEvent.php
│   │   │   │       └── UpdateStatusRequestDomainEvent.php
│   │   │   └── RequestRepository.php
│   │   └── Infrastructure
│   │       ├── Controller
│   │       │   ├── Api
│   │       │   │   └── ApiRequestController.php
│   │       │   ├── RequestController.php
│   │       │   └── RequestManagementController.php
│   │       ├── Form
│   │       │   └── CreateRequestType.php
│   │       ├── Framework
│   │       │   └── EventSubscriber
│   │       │       ├── SendEmailOnRequestStatusChanged.php
│   │       │       └── UpdateUserOnRequestSaved.php
│   │       ├── Persistence
│   │       │   └── Doctrine
│   │       │       └── Mapping
│   │       │           └── Request.orm.yml
│   │       └── Repository
│   │           └── RequestDoctrineRepository.php
│   ├── Role
│   │   ├── Application
│   │   │   ├── Api
│   │   │   │   ├── GetApiRoles.php
│   │   │   │   ├── GetApiRolesRequest.php
│   │   │   │   └── RoleResponse.php
│   │   │   └── Search
│   │   │       ├── GetArrayRolesById.php
│   │   │       ├── GetArrayRolesByIdRequest.php
│   │   │       ├── GetRole.php
│   │   │       ├── GetRoleById.php
│   │   │       ├── GetRoleByIdRequest.php
│   │   │       ├── GetRoleByName.php
│   │   │       ├── GetRoleByNameRequest.php
│   │   │       ├── GetRoleRequest.php
│   │   │       ├── GetRoles.php
│   │   │       ├── GetRolesById.php
│   │   │       └── GetRolesRequest.php
│   │   ├── Domain
│   │   │   ├── Exception
│   │   │   │   └── RoleNotExistsException.php
│   │   │   ├── Model
│   │   │   │   ├── Aggregate
│   │   │   │   │   └── Role.php
│   │   │   │   └── Exception
│   │   │   │       ├── InvalidRoleException.php
│   │   │   │       └── NotExistsWorkPositionException.php
│   │   │   └── RoleRepository.php
│   │   └── Infrastructure
│   │       ├── Controller
│   │       │   └── Api
│   │       │       └── ApiRoleController.php
│   │       ├── Persistence
│   │       │   └── Doctrine
│   │       │       └── Mapping
│   │       │           └── Role.orm.yml
│   │       └── Repository
│   │           └── RolesDoctrineRepository.php
│   ├── Shared
│   │   ├── Domain
│   │   │   ├── Model
│   │   │   │   ├── Aggregate
│   │   │   │   │   └── Aggregate.php
│   │   │   │   └── Event
│   │   │   │       ├── DomainEvent.php
│   │   │   │       └── DomainEventBus.php
│   │   │   └── ValueObject
│   │   │       ├── Email.php
│   │   │       └── IdentUuid.php
│   │   └── Infrastructure
│   │       ├── CommandBus
│   │       │   └── TacticianHandlerInflector.php
│   │       ├── Event
│   │       │   ├── EventDispatcherDomainEventBus.php
│   │       │   └── MessengerDomainEventBus.php
│   │       ├── Persistence
│   │       │   └── Doctrine
│   │       │       └── Type
│   │       │           └── IdentUuidType.php
│   │       └── Utils
│   │           ├── File.php
│   │           ├── Formatter.php
│   │           ├── Slugger.php
│   │           ├── SluggerInterface.php
│   │           └── Sluggers
│   │               └── CocurSlugger.php
│   ├── StatusRequest
│   │   ├── Application
│   │   │   ├── Api
│   │   │   │   ├── GetApiStatusRequests.php
│   │   │   │   ├── GetApiStatusRequestsRequest.php
│   │   │   │   └── StatusRequestResponse.php
│   │   │   └── Search
│   │   │       ├── GetStatusRequest.php
│   │   │       └── GetStatusRequestRequest.php
│   │   ├── Domain
│   │   │   ├── Exception
│   │   │   │   └── StatusRequestIdNotExistsException.php
│   │   │   ├── Model
│   │   │   │   └── Aggregate
│   │   │   │       └── StatusRequest.php
│   │   │   └── StatusRequestRepository.php
│   │   └── Infrastructure
│   │       ├── Controller
│   │       │   └── Api
│   │       │       └── ApiStatusRequestController.php
│   │       └── Persistence
│   │           ├── Doctrine
│   │           │   └── Mapping
│   │           │       └── StatusRequest.orm.yml
│   │           └── Repository
│   │               └── DoctrineStatusRequestRepository.php
│   ├── TypeRequest
│   │   ├── Application
│   │   │   ├── Api
│   │   │   │   ├── GetApiTypesRequest.php
│   │   │   │   ├── GetApiTypesRequestRequest.php
│   │   │   │   └── TypeRequestResponse.php
│   │   │   └── Search
│   │   │       ├── GetAllTypeRequests.php
│   │   │       ├── GetAllTypesRequestsRequest.php
│   │   │       ├── GetTypeRequest.php
│   │   │       └── GetTypeRequestRequest.php
│   │   ├── Domain
│   │   │   ├── Exception
│   │   │   │   └── TypeRequestIdNotExistsException.php
│   │   │   ├── Model
│   │   │   │   └── Aggregate
│   │   │   │       └── TypeRequest.php
│   │   │   └── TypeRequestRepository.php
│   │   └── Infrastructure
│   │       ├── Controller
│   │       │   └── Api
│   │       │       └── ApiTypeRequestController.php
│   │       ├── Form
│   │       │   └── CreateTypeRequestType.php
│   │       └── Persistence
│   │           ├── Doctrine
│   │           │   └── Mapping
│   │           │       └── TypeRequest.orm.yml
│   │           └── Repository
│   │               └── DoctrineTypeRequestRepository.php
│   ├── User
│   │   ├── Application
│   │   │   ├── Api
│   │   │   │   ├── GetApiUsers.php
│   │   │   │   ├── GetApiUsersRequest.php
│   │   │   │   └── UserResponse.php
│   │   │   ├── Create
│   │   │   │   ├── CreateUser.php
│   │   │   │   └── CreateUserRequest.php
│   │   │   ├── GetUsersCompanyRequest.php
│   │   │   ├── Register
│   │   │   │   ├── UserRegister.php
│   │   │   │   └── UserRegisterRequest.php
│   │   │   ├── Search
│   │   │   │   ├── GetUser.php
│   │   │   │   ├── GetUserRequest.php
│   │   │   │   ├── GetUsers.php
│   │   │   │   ├── GetUsersByCompany.php
│   │   │   │   └── GetUsersRequest.php
│   │   │   ├── Security
│   │   │   │   ├── RequestToken.php
│   │   │   │   ├── RequestTokenRequest.php
│   │   │   │   ├── UpdatePassword.php
│   │   │   │   ├── UpdatePasswordRequest.php
│   │   │   │   ├── ValidateToken.php
│   │   │   │   └── ValidateTokenRequest.php
│   │   │   └── Update
│   │   │       ├── UpdateUser.php
│   │   │       ├── UpdateUserAvailableDays.php
│   │   │       ├── UpdateUserAvailableDaysRequest.php
│   │   │       └── UpdateUserRequest.php
│   │   ├── Domain
│   │   │   ├── Exception
│   │   │   │   ├── TokenNotExistsException.php
│   │   │   │   ├── UserRegisterServiceNotAvailableException.php
│   │   │   │   └── UsersCompaniesNotAvailableException.php
│   │   │   ├── Model
│   │   │   │   ├── Aggregate
│   │   │   │   │   └── User.php
│   │   │   │   ├── Event
│   │   │   │   │   ├── RequestTokenDomainEvent.php
│   │   │   │   │   ├── UpdatedUserAvailableDaysDomainEvent.php
│   │   │   │   │   ├── UserCreatedDomainEvent.php
│   │   │   │   │   └── UserRegisteredDomainEvent.php
│   │   │   │   ├── Exception
│   │   │   │   │   ├── InvalidPasswordException.php
│   │   │   │   │   ├── TokenNotExistException.php
│   │   │   │   │   ├── UserAlreadyHasRoleException.php
│   │   │   │   │   ├── UserEmailNotExistsException.php
│   │   │   │   │   ├── UserExistsException.php
│   │   │   │   │   ├── UserNotExistsException.php
│   │   │   │   │   ├── UserNotHasRoleException.php
│   │   │   │   │   └── UserUpdateAvailableDaysException.php
│   │   │   │   ├── Factory
│   │   │   │   │   └── UserFactory.php
│   │   │   │   └── ValueObject
│   │   │   │       └── Password.php
│   │   │   └── UserRepository.php
│   │   └── Infrastructure
│   │       ├── Controller
│   │       │   ├── Api
│   │       │   │   └── ApiUserController.php
│   │       │   ├── ProfileController.php
│   │       │   └── UserController.php
│   │       ├── Framework
│   │       │   ├── EventSubscriber
│   │       │   │   └── RecoveryTokenOnUserCreated.php
│   │       │   ├── Form
│   │       │   │   ├── CreateUserType.php
│   │       │   │   ├── RequestTokenType.php
│   │       │   │   ├── RestoreEmailAndPasswordType.php
│   │       │   │   ├── RestorePasswordType.php
│   │       │   │   └── UserType.php
│   │       │   └── Security
│   │       │       ├── LoginFormAuthenticator.php
│   │       │       ├── RequestTokenController.php
│   │       │       ├── RestorePasswordController.php
│   │       │       └── SecurityController.php
│   │       ├── Model
│   │       │   └── Factory
│   │       │       └── SymfonyUserFactory.php
│   │       ├── Persistence
│   │       │   └── Doctrine
│   │       │       └── Mapping
│   │       │           └── User.orm.yml
│   │       └── Repository
│   │           └── UserDoctrineRepository.php
│   ├── WorkPlace
│   │   ├── Application
│   │   │   ├── Api
│   │   │   │   ├── GetApiWorkPlaces.php
│   │   │   │   ├── GetApiWorkPlacesRequest.php
│   │   │   │   └── WorkPlaceResponse.php
│   │   │   ├── Create
│   │   │   │   ├── CreateWorkPlace.php
│   │   │   │   └── CreateWorkPlaceRequest.php
│   │   │   ├── Find
│   │   │   │   ├── FindWorkPlace.php
│   │   │   │   └── FindWorkPlaceRequest.php
│   │   │   ├── Get
│   │   │   │   ├── GetWorkPlaces.php
│   │   │   │   └── GetWorkPlacesRequest.php
│   │   │   ├── Search
│   │   │   │   ├── GetWorkPlace.php
│   │   │   │   ├── GetWorkPlaceRequest.php
│   │   │   │   └── GetWorkplaceByCompany.php
│   │   │   └── Update
│   │   │       ├── UpdateWorkPlace.php
│   │   │       └── UpdateWorkPlaceRequest.php
│   │   ├── Domain
│   │   │   ├── Exception
│   │   │   │   ├── WorkPlaceAlreadyExistsException.php
│   │   │   │   ├── WorkPlaceIdNotExistsException.php
│   │   │   │   ├── WorkPlaceNotBlockedException.php
│   │   │   │   └── WorkPlaceServiceIsNotAvailableException.php
│   │   │   ├── Model
│   │   │   │   └── Aggregate
│   │   │   │       └── WorkPlace.php
│   │   │   └── WorkPlaceRepository.php
│   │   └── Infrastructure
│   │       ├── Controller
│   │       │   ├── Api
│   │       │   │   └── ApiWorkPlaceController.php
│   │       │   └── WorkPlaceController.php
│   │       ├── Form
│   │       │   └── CreateWorkPlaceType.php
│   │       ├── Persistence
│   │       │   └── Doctrine
│   │       │       └── Mapping
│   │       │           └── WorkPlace.orm.yml
│   │       └── Repository
│   │           └── WorkPlaceDoctrineRepository.php
│   └── WorkPosition
│       ├── Application
│       │   ├── Api
│       │   │   ├── GetApiWorkPositions.php
│       │   │   ├── GetApiWorkPositionsRequest.php
│       │   │   └── WorkPositionResponse.php
│       │   ├── Create
│       │   │   ├── CreateWorkPosition.php
│       │   │   └── CreateWorkPositionRequest.php
│       │   ├── Find
│       │   │   ├── FindWorkPosition.php
│       │   │   └── FindWorkPositionRequest.php
│       │   ├── Get
│       │   │   ├── GetWorkPositions.php
│       │   │   └── GetWorkPositionsRequest.php
│       │   ├── Search
│       │   │   ├── GetWorkPosition.php
│       │   │   ├── GetWorkPositionByCompany.php
│       │   │   ├── GetWorkPositionByCompanyRequest.php
│       │   │   ├── GetWorkPositionByDepartment.php
│       │   │   └── GetWorkPositionRequest.php
│       │   └── Update
│       │       ├── UpdateWorkPosition.php
│       │       └── UpdateWorkPositionRequest.php
│       ├── Domain
│       │   ├── Exception
│       │   │   ├── WorkPositionAlreadyExistsException.php
│       │   │   ├── WorkPositionIdNotExistsException.php
│       │   │   └── WorkPositionServiceIsNotAvailableException.php
│       │   ├── Model
│       │   │   └── Aggregate
│       │   │       └── WorkPosition.php
│       │   └── WorkPositionRepository.php
│       └── Infrastructure
│           ├── Controller
│           │   ├── Api
│           │   │   └── ApiWorkPositionController.php
│           │   └── WorkPositionController.php
│           ├── Form
│           │   └── CreateWorkPositionType.php
│           ├── Persistence
│           │   └── Doctrine
│           │       └── Mapping
│           │           └── WorkPosition.orm.yml
│           └── Repository
│               └── WorkPositionDoctrineRepository.php
```

## ✅ Test & API REST

En nuestro proyecto hemos realizado test unitarios con [PHPUnit](phpunit.xml.dist), donde realizamos comprobaciones como 
por ejemplo: se podría autenticar cuando el usuario existe en [UsersTest](tests/Application/UsersTest.php)  y otros test
como aparecen en la siguiente estructura:

```scala
$ tree

├── Tests
    ├── Application
    │   ├── HolidaysTest.php
    │   ├── RolesTest.php
    │   └── UsersTest.php
    └── bootstrap.php
```

A nivel de API REST, se ha realizado la implementación para que todos los módulos de la aplicación retornen un JSON como respuesta, por
ejemplo: en el módulo de User el [ApiUserController](src/User/Infrastructure/Controller/Api/ApiUserController.php) recibe 
la petición con el parámetro indicado para que el [GetApiUsers](src/User/Application/Api/GetApiUsers.php) le retorne los
datos en un JSON. Estructura de API Rest para el módulo de User:

```scala
$ tree

├── User
    ├── Application
    │   ├── Api
    │       ├── GetApiUsers.php
    │       ├── GetApiUsersRequest.php
    │       └── UserResponse.php
    ├── Domain
    └── Infrastructure
        ├── Controller
            ├── Api
                └── ApiUserController.php    
```  

## 🤔 Consideraciones

*  Se ha utilizado:
    *   [php7.4](https://www.php.net/downloads.php#v7.4.10) 
    *   [Symfony 4.4.10](https://symfony.com/doc/4.4/index.html)
    *   [dropzone](https://www.dropzonejs.com/)
    *   [AdminLTE](https://adminlte.io/)
