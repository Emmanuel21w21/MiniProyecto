# Mini e-commerce

Aplicación Laravel de venta de productos con login manual, catálogo, categorías, ventas, roles y bitácoras.

## Puesta en marcha

Requiere PHP 8.3 o superior con `mbstring`, `pdo_sqlite` (o el driver de base de datos elegido) y Composer.

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Las cuentas de demostración creadas por `DatabaseSeeder` son:

| Rol | Correo | Contraseña |
|---|---|---|
| Administrador | `admin@ecommerce.test` | `password` |
| Gerente | `gerente@ecommerce.test` | `password` |
| Cliente | `cliente@ecommerce.test` | `password` |

`UsuarioFactory` usa únicamente los nombres y apellidos de la consigna, genera el correo `inicialapellido@tuxtla.tecnm.mx` y guarda la contraseña `123` con hash.

## Entidades y relaciones

- `Usuario` (`app/Models/Usuario.php`) autentica usando `correo` y `clave`; tiene productos, ventas como cliente y ventas como vendedor.
- `Producto` pertenece al vendedor (`usuario_id`), tiene muchas ventas y se relaciona muchos-a-muchos con categorías.
- `Categoria` tiene muchos productos mediante `categoria_producto`.
- `Venta` pertenece al producto, cliente y vendedor.
- `Usuario::categorias()` usa una relación Eloquent `HasManyThrough` que recorre productos y une la tabla pivote. El producto-categoría sigue siendo una relación muchos-a-muchos; esa tabla intermedia adicional es necesaria para conservar ambas relaciones.

Las migraciones de dominio están en `database/migrations/`: actualización compatible de usuarios, catálogo y pivote, y ventas.

## Roles y autorización

Valores de `rol`: `administrador`, `gerente`, `cliente`.

- El administrador crea, edita y elimina usuarios.
- El gerente puede editar exclusivamente clientes; las políticas impiden editar administradores o gerentes.
- El gerente administra productos propios y categorías.
- Los clientes consultan el catálogo y registran compras; una transacción valida y descuenta existencias.
- Las acciones se autorizan con `authorize()` y las Policies en `app/Policies/`. Los Gates `administrar-tienda` y `administrar-usuarios` se definen en `AppServiceProvider` y restringen rutas.

## Validación, datos y bitácoras

Los controladores reciben `FormRequest` (`app/Http/Requests/`) para login, registro, usuarios, productos, categorías y ventas. Los seeders `CategoriaSeeder` y `ProductoSeeder` cargan catálogo de ejemplo.

Los canales `autenticacion`, `productos` y `ventas`, definidos en `config/logging.php`, escriben respectivamente en:

- `storage/logs/autenticacion.log`
- `storage/logs/productos.log`
- `storage/logs/ventas.log`

Se registran login correcto/incorrecto y logout, alta/edición/eliminación de productos y creación de ventas. No se registran contraseñas.

## Rutas y pruebas

Las rutas web están en `routes/web.php`. Para probar:

```powershell
php artisan test
```
