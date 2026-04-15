"""
Modelo de sesión para el administrador autenticado.
Utiliza flask-login's UserMixin para gestión de sesiones.
"""
from flask_login import UserMixin


class AdminSession(UserMixin):
    """Representa la sesión del administrador autenticado."""
    def __init__(self, admin_id, nombre, email):
        self.id = str(admin_id)
        self.nombre = nombre
        self.email = email
