from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy.orm import Session
from typing import List

from app import models, schemas, database
from app.database import get_db
from app.security.helpers import get_password_hash, verify_password

router = APIRouter(
    prefix="/usuarios",
    tags=["Clientes"]
)

@router.get("/", response_model=List[schemas.UsuarioResponse])
def read_usuarios(skip: int = 0, limit: int = 100, db: Session = Depends(database.get_db)):
    usuarios = db.query(models.Usuario).offset(skip).limit(limit).all()
    return usuarios

@router.post("/", response_model=schemas.UsuarioResponse)
def create_usuario(usuario: schemas.UsuarioCreate, db: Session = Depends(database.get_db)):
    db_usuario = db.query(models.Usuario).filter(models.Usuario.correo == usuario.correo).first()
    if db_usuario:
        raise HTTPException(status_code=400, detail="Correo ya registrado")
    
    hashed_password = get_password_hash(usuario.password)
    db_usuario = models.Usuario(
        nombre=usuario.nombre, 
        correo=usuario.correo,
        password_hash=hashed_password
    )
    db.add(db_usuario)
    db.commit()
    db.refresh(db_usuario)
    return db_usuario

@router.post("/login", tags=["Autentificación"])
def login_usuario(usuario: schemas.UsuarioLogin, db: Session = Depends(database.get_db)):
    db_usuario = db.query(models.Usuario).filter(models.Usuario.correo == usuario.correo).first()
    if not db_usuario or not verify_password(usuario.password, db_usuario.password_hash):
        raise HTTPException(status_code=401, detail="Credenciales incorrectas")
    
    return {
        "id": db_usuario.id,
        "nombre": db_usuario.nombre,
        "correo": db_usuario.correo,
        "message": "Login exitoso"
    }


@router.put("/{usuario_id}", response_model=schemas.UsuarioResponse)
def update_usuario(usuario_id: int, usuario: schemas.UsuarioBase, db: Session = Depends(database.get_db)):
    db_usuario = db.query(models.Usuario).filter(models.Usuario.id == usuario_id).first()
    if not db_usuario:
        raise HTTPException(status_code=404, detail="Usuario no encontrado")
    
    db_usuario.nombre = usuario.nombre
    db_usuario.correo = usuario.correo
    db.commit()
    db.refresh(db_usuario)
    return db_usuario

@router.patch("/{usuario_id}/password")
def update_usuario_password(usuario_id: int, data: schemas.UsuarioPasswordUpdate, db: Session = Depends(database.get_db)):
    db_usuario = db.query(models.Usuario).filter(models.Usuario.id == usuario_id).first()
    if not db_usuario:
        raise HTTPException(status_code=404, detail="Usuario no encontrado")
    
    if not verify_password(data.current_password, db_usuario.password_hash):
        raise HTTPException(status_code=400, detail="La contraseña actual es incorrecta")
    
    db_usuario.password_hash = get_password_hash(data.new_password)
    db.commit()
    return {"message": "Contraseña actualizada exitosamente"}


@router.delete("/{usuario_id}")
def delete_usuario(usuario_id: int, db: Session = Depends(database.get_db)):
    db_usuario = db.query(models.Usuario).filter(models.Usuario.id == usuario_id).first()
    if not db_usuario:
        raise HTTPException(status_code=404, detail="Usuario no encontrado")
    db.delete(db_usuario)
    db.commit()
    return {"message": "Usuario eliminado"}
