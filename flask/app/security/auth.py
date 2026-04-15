"""
Configuración de Flask-Login y callback user_loader.
Extraído de models.py para separar la lógica de autenticación.
"""
import requests
from flask_login import LoginManager

from app.models.admin_session import AdminSession
from app.core.config import Config

login_manager = LoginManager()
login_manager.login_view = 'main.index'
login_manager.login_message = "Por favor inicie sesión para acceder a esta página."


@login_manager.user_loader
def load_user(user_id):
    """Carga la sesión del usuario desde la API de FastAPI."""
    try:
        response = requests.get(f"{Config.FASTAPI_URL}/admins/")
        if response.status_code == 200:
            admins = response.json()
            for admin in admins:
                if str(admin['id']) == user_id:
                    return AdminSession(admin['id'], admin['nombre'], admin['email'])
    except Exception as e:
        print("Error fetching user from API:", e)
    return None
