"""
Funciones de hashing y verificación de contraseñas.
Extraídas de main.py para centralizar la lógica de seguridad.
"""
from werkzeug.security import generate_password_hash, check_password_hash


def verify_password(plain_password: str, hashed_password: str) -> bool:
    """Verifica una contraseña plana contra su hash."""
    return check_password_hash(hashed_password, plain_password)


def get_password_hash(password: str) -> str:
    """Genera un hash seguro de la contraseña."""
    return generate_password_hash(password)
