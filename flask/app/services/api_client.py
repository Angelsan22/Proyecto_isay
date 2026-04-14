"""
Servicio cliente para comunicarse con la API de FastAPI.
Centraliza todas las llamadas HTTP al backend.
"""
import requests
from app.core.config import Config

API_URL = Config.FASTAPI_URL


class ApiClient:
    """Cliente centralizado para comunicación con la API de FastAPI."""

    # --- Admins ---
    @staticmethod
    def login_admin(email: str, password: str):
        return requests.post(f"{API_URL}/admins/login", json={"email": email, "password": password})

    @staticmethod
    def get_admins():
        return requests.get(f"{API_URL}/admins/")

    @staticmethod
    def create_admin(payload: dict):
        return requests.post(f"{API_URL}/admins/", json=payload)

    # --- Usuarios ---
    @staticmethod
    def get_usuarios():
        return requests.get(f"{API_URL}/usuarios/")

    # --- Pedidos ---
    @staticmethod
    def get_pedidos(params: dict = None):
        return requests.get(f"{API_URL}/pedidos/", params=params)

    # --- Productos ---
    @staticmethod
    def get_productos(params: dict = None):
        return requests.get(f"{API_URL}/productos/", params=params)

    @staticmethod
    def get_producto(producto_id: int):
        return requests.get(f"{API_URL}/productos/{producto_id}")

    @staticmethod
    def create_producto(payload: dict):
        return requests.post(f"{API_URL}/productos/", json=payload)

    @staticmethod
    def update_producto(producto_id: int, payload: dict):
        return requests.put(f"{API_URL}/productos/{producto_id}", json=payload)
