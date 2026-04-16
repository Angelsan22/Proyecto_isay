from flask_login import UserMixin
from app import login_manager
import requests

import os

API_URL = os.environ.get("FASTAPI_URL", "http://127.0.0.1:8000")

class AdminSession(UserMixin):
    def __init__(self, admin_id, nombre, email):
        self.id = str(admin_id)
        self.nombre = nombre
        self.email = email

@login_manager.user_loader
def load_user(user_id):
    try:
        response = requests.get(f"{API_URL}/admins/")
        if response.status_code == 200:
            admins = response.json()
            for admin in admins:
                if str(admin['id']) == user_id:
                    return AdminSession(admin['id'], admin['nombre'], admin['email'])
    except Exception as e:
        print("Error fetching user from API:", e)
    return None
