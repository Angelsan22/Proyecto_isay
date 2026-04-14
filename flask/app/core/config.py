"""
Configuración global de la aplicación Flask.
Centraliza todas las variables de entorno y constantes.
Reemplaza al antiguo config.py de la raíz del proyecto.
"""
import os

basedir = os.path.abspath(os.path.join(os.path.dirname(__file__), '..', '..'))


class Config:
    SECRET_KEY = os.environ.get("SECRET_KEY", "clave_super_secreta_123")
    SQLALCHEMY_DATABASE_URI = 'sqlite:///' + os.path.join(basedir, 'app.db')
    SQLALCHEMY_TRACK_MODIFICATIONS = False

    # URL del backend FastAPI
    FASTAPI_URL = os.environ.get("FASTAPI_URL", "http://127.0.0.1:8000")
