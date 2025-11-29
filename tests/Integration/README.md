# Tests de Integración - Factus SDK

## Configuración

1. **Edita el archivo `.env.testing`** con tus credenciales reales de Factus:

```bash
FACTUS_CLIENT_ID=tu-client-id-real
FACTUS_CLIENT_SECRET=tu-client-secret-real
FACTUS_USERNAME=tu-username-real
FACTUS_PASSWORD=tu-password-real
FACTUS_PRODUCTION=false  # false para sandbox, true para producción
```


## Ejecutar Tests

### Ejecutar solo tests de integración:
```bash
composer test -- --group=integration
```

### Ejecutar tests normales (sin integración):
```bash
composer test -- --exclude-group=integration
```

### Ejecutar todos los tests:
```bash
composer test
```

### Ejecutar un test específico:
```bash
vendor/bin/pest tests/Integration/FactusHttpClientIntegrationTest.php
```

## Notas Importantes

- Los tests de integración **SÍ hacen peticiones reales** a la API de Factus
- Usa el ambiente **sandbox** para pruebas (FACTUS_PRODUCTION=false)
- Ajusta los datos de prueba según los valores válidos de tu cuenta Factus

## Estructura del Test

El test incluye:

1. **Test de autenticación**: Verifica que puedes obtener un access token
2. **Test de creación de factura**: Crea y valida una factura completa
3. **Test de errores**: Verifica manejo de credenciales inválidas (opcional)
