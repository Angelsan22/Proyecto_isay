"""
Servicio de lógica de negocio para Administradores.
Separa la lógica de la capa de enrutamiento (routers).
"""
from sqlalchemy.orm import Session
from fastapi import HTTPException

from app.models.admin import Admin
from app.schemas.admin import AdminCreate
from app.security.hashing import get_password_hash, verify_password


def get_admins(db: Session, skip: int = 0, limit: int = 100):
    """Obtiene la lista de administradores."""
    return db.query(Admin).offset(skip).limit(limit).all()


def create_admin(db: Session, admin: AdminCreate):
    """Crea un nuevo administrador con contraseña hasheada."""
    db_admin = db.query(Admin).filter(Admin.email == admin.email).first()
    if db_admin:
        raise HTTPException(status_code=400, detail="Email ya registrado")

    hashed_password = get_password_hash(admin.password)
    db_admin = Admin(
        nombre=admin.nombre,
        email=admin.email,
        password_hash=hashed_password,
        creador_id=admin.creador_id
    )
    db.add(db_admin)
    db.commit()
    db.refresh(db_admin)
    return db_admin


def authenticate_admin(db: Session, email: str, password: str):
    """Autentica un administrador por email y contraseña."""
    db_admin = db.query(Admin).filter(Admin.email == email).first()
    if not db_admin or not verify_password(password, db_admin.password_hash):
        raise HTTPException(status_code=401, detail="Credenciales incorrectas")

    return {
        "message": "Login exitoso",
        "admin_id": db_admin.id,
        "nombre": db_admin.nombre,
        "email": db_admin.email
    }


def delete_admin(db: Session, admin_id: int):
    """Elimina un administrador por ID."""
    db_admin = db.query(Admin).filter(Admin.id == admin_id).first()
    if not db_admin:
        raise HTTPException(status_code=404, detail="Administrador no encontrado")
    db.delete(db_admin)
    db.commit()
    return {"message": "Admin eliminado"}
