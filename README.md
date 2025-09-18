# 🎓 SGA-SICEFA
### Sistema de Gestión Académica - SICEFA
*Aplicación modular Laravel para gestión integral de procesos académicos y administrativos*

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/AdminLTE-3C8DBC?style=for-the-badge&logo=bootstrap&logoColor=white" alt="AdminLTE">
</p>

<p align="center">
  <img src="https://img.shields.io/github/license/Fer969/sga-sicefa?style=flat-square" alt="License">
  <img src="https://img.shields.io/github/last-commit/Fer969/sga-sicefa?style=flat-square" alt="Last Commit">
  <img src="https://img.shields.io/github/languages/count/Fer969/sga-sicefa?style=flat-square" alt="Languages">
</p>

---

## 📋 Descripción

SGA-SICEFA es un sistema integral de gestión académica desarrollado con Laravel que permite administrar múltiples procesos educativos y administrativos a través de una arquitectura modular. El sistema está diseñado para instituciones educativas que requieren una solución completa y escalable.

## ✨ Características Principales

- 🏗️ **Arquitectura Modular**: 17 módulos independientes para diferentes procesos
- 🎨 **Interfaz Moderna**: Basada en AdminLTE para una experiencia de usuario intuitiva
- 📊 **Reportes y Gráficos**: Integración con Highcharts para visualización de datos
- 👥 **Gestión de Usuarios**: Sistema completo de roles y permisos
- 📱 **Responsive Design**: Compatible con dispositivos móviles y tablets
- 🔒 **Seguridad**: Implementación de mejores prácticas de seguridad Laravel
- 📈 **Escalable**: Diseño preparado para crecimiento institucional

## 🧩 Módulos del Sistema

| Módulo | Descripción | Archivos |
|--------|-------------|----------|
| **SICA** | Sistema de Información y Control Académico | 353 |
| **SIGAC** | Sistema de Gestión Académica y Curricular | 210 |
| **SENAEMPRESA** | Gestión empresarial y productiva | 124 |
| **HANGARAUTO** | Gestión automotriz | 113 |
| **CAFETO** | Gestión de cultivos de café | 106 |
| **SGA** | Sistema de Gestión Académica | 105 |
| **PTVENTA** | Punto de venta | 92 |
| **AGROINDUSTRIA** | Gestión agroindustrial | 92 |
| **HDC** | Gestión de datos clínicos | 90 |
| **CEFAMAPS** | Mapas y geolocalización | 85 |
| **EVS** | Evaluación y seguimiento | 65 |
| **GTH** | Gestión del talento humano | 62 |
| **CPD** | Desarrollo profesional continuo | 59 |
| **PQRS** | Peticiones, quejas, reclamos y sugerencias | 51 |
| **TILABS** | Laboratorios TI | 39 |
| **BOLMETEOR** | Información meteorológica | 38 |
| **AGROCEFA** | Gestión agropecuaria | 131 |
| **BIENESTAR** | Bienestar institucional | - |

## 🚀 Instalación

### Prerrequisitos

- PHP >= 8.0
- Composer
- Node.js >= 14.x
- MySQL >= 5.7
- Apache/Nginx

### Pasos de instalación

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/Fer969/sga-sicefa.git
   cd sga-sicefa
   ```

2. **Instalar dependencias de PHP**
   ```bash
   composer install
   ```

3. **Instalar dependencias de Node.js**
   ```bash
   npm install
   ```

4. **Configurar variables de entorno**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configurar base de datos**
   - Editar el archivo `.env` con los datos de tu base de datos
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sga_sicefa
   DB_USERNAME=tu_usuario
   DB_PASSWORD=tu_contraseña
   ```

6. **Ejecutar migraciones**
   ```bash
   php artisan migrate
   ```

7. **Ejecutar seeders (opcional)**
   ```bash
   php artisan db:seed
   ```

8. **Compilar assets**
   ```bash
   npm run dev
   # o para producción
   npm run build
   ```

9. **Configurar permisos**
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

## 🔧 Configuración

### Módulos
El sistema utiliza [nwidart/laravel-modules](https://github.com/nwidart/laravel-modules) para la gestión modular. Cada módulo tiene su propia estructura MVC.

### Permisos de archivos
```bash
# Dar permisos a las carpetas necesarias
sudo chown -R www-data:www-data storage
sudo chown -R www-data:www-data bootstrap/cache
```

### Configuración del servidor web
Asegúrate de que el documento root apunte a la carpeta `public/`.

## 📖 Uso

1. **Acceder al sistema**
   - Navega a tu dominio configurado
   - Utiliza las credenciales por defecto o las configuradas en los seeders

2. **Navegación por módulos**
   - Cada módulo tiene su propia interfaz accesible desde el menú principal
   - Los permisos se gestionan por roles de usuario

## 🛠️ Tecnologías Utilizadas

- **Backend**: Laravel 9.x
- **Frontend**: AdminLTE, Bootstrap, jQuery
- **Base de datos**: MySQL
- **Gráficos**: Highcharts
- **Gestión de archivos**: Laravel File Manager
- **Exportación**: Laravel Excel, DomPDF
- **Autenticación**: Laravel Sanctum
- **Auditoría**: Laravel Auditing

## 🤝 Contribución

Las contribuciones son bienvenidas. Para contribuir:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📝 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo [LICENSE](LICENSE) para más detalles.

## 👨‍💻 Autor

**Luisfer Fuentes Montoya** - [@Fer969](https://github.com/Fer969)

## 📞 Soporte

Si tienes preguntas o necesitas soporte:
- 📧 Email: luisferfuentesmontoya@gmail.com
- 🐛 Issues: [GitHub Issues](https://github.com/Fer969/sga-sicefa/issues)

---

<p align="center">
  Desarrollado con ❤️ para la gestión académica moderna
</p>