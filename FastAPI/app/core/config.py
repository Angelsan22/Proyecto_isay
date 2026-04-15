"""
Configuración global de la aplicación FastAPI.
Centraliza todas las variables de entorno y constantes del proyecto.
"""
import os


class Settings:
    APP_TITLE: str = "Maccuin API"
    APP_DESCRIPTION: str = "Backend centralizado"
    APP_VERSION: str = "1.0"

    # Base de datos
    DATABASE_URL: str = os.environ.get(
        "DATABASE_URL", "sqlite:///./api.db"
    )

    # Seguridad
    SECRET_KEY: str = os.environ.get(
        "SECRET_KEY", "clave_super_secreta_fastapi_123"
    )
    ACCESS_TOKEN_EXPIRE_MINUTES: int = 60


settings = Settings()
