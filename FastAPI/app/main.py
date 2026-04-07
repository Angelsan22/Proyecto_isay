from fastapi import FastAPI, Depends, HTTPException, status
from sqlalchemy.orm import Session
from typing import List
from werkzeug.security import generate_password_hash, check_password_hash

from app import models, schemas, database
from app.database import engine

# Crear tablas
models.Base.metadata.create_all(bind=engine)

app = FastAPI(
    title="Maccuin API",
    description="Backend centralizado",
    version="1.0"
)

# Password hashing
def verify_password(plain_password, hashed_password):
    return check_password_hash(hashed_password, plain_password)

def get_password_hash(password):
    return generate_password_hash(password)

# --- RUTAS DE ADMINISTRADORES ---

@app.get("/admins/", response_model=List[schemas.AdminResponse])
def read_admins(skip: int = 0, limit: int = 100, db: Session = Depends(database.get_db)):
    admins = db.query(models.Admin).offset(skip).limit(limit).all()
    # Populate creador string info if needed, but returning just models is fine
    return admins

@app.post("/admins/", response_model=schemas.AdminResponse)
def create_admin(admin: schemas.AdminCreate, db: Session = Depends(database.get_db)):
    db_admin = db.query(models.Admin).filter(models.Admin.email == admin.email).first()
    if db_admin:
        raise HTTPException(status_code=400, detail="Email ya registrado")
    
    hashed_password = get_password_hash(admin.password)
    db_admin = models.Admin(
        nombre=admin.nombre,
        email=admin.email,
        password_hash=hashed_password,
        creador_id=admin.creador_id
    )
    db.add(db_admin)
    db.commit()
    db.refresh(db_admin)
    return db_admin

@app.post("/admins/login")
def login_admin(admin: schemas.AdminLogin, db: Session = Depends(database.get_db)):
    db_admin = db.query(models.Admin).filter(models.Admin.email == admin.email).first()
    if not db_admin or not verify_password(admin.password, db_admin.password_hash):
        raise HTTPException(status_code=401, detail="Credenciales incorrectas")
    
    return {"message": "Login exitoso", "admin_id": db_admin.id, "nombre": db_admin.nombre, "email": db_admin.email}

@app.delete("/admins/{admin_id}")
def delete_admin(admin_id: int, db: Session = Depends(database.get_db)):
    db_admin = db.query(models.Admin).filter(models.Admin.id == admin_id).first()
    if not db_admin:
        raise HTTPException(status_code=404, detail="Administrador no encontrado")
    db.delete(db_admin)
    db.commit()
    return {"message": "Admin eliminado"}

# --- RUTAS DE USUARIOS REGULARES ---

@app.get("/usuarios/", response_model=List[schemas.UsuarioResponse])
def read_usuarios(skip: int = 0, limit: int = 100, db: Session = Depends(database.get_db)):
    usuarios = db.query(models.Usuario).offset(skip).limit(limit).all()
    return usuarios

@app.post("/usuarios/", response_model=schemas.UsuarioResponse)
def create_usuario(usuario: schemas.UsuarioCreate, db: Session = Depends(database.get_db)):
    db_usuario = db.query(models.Usuario).filter(models.Usuario.correo == usuario.correo).first()
    if db_usuario:
        raise HTTPException(status_code=400, detail="Correo ya registrado")
    
    db_usuario = models.Usuario(nombre=usuario.nombre, correo=usuario.correo)
    db.add(db_usuario)
    db.commit()
    db.refresh(db_usuario)
    return db_usuario

@app.put("/usuarios/{usuario_id}", response_model=schemas.UsuarioResponse)
def update_usuario(usuario_id: int, usuario: schemas.UsuarioCreate, db: Session = Depends(database.get_db)):
    db_usuario = db.query(models.Usuario).filter(models.Usuario.id == usuario_id).first()
    if not db_usuario:
        raise HTTPException(status_code=404, detail="Usuario no encontrado")
    
    db_usuario.nombre = usuario.nombre
    db_usuario.correo = usuario.correo
    db.commit()
    db.refresh(db_usuario)
    return db_usuario

@app.delete("/usuarios/{usuario_id}")
def delete_usuario(usuario_id: int, db: Session = Depends(database.get_db)):
    db_usuario = db.query(models.Usuario).filter(models.Usuario.id == usuario_id).first()
    if not db_usuario:
        raise HTTPException(status_code=404, detail="Usuario no encontrado")
    db.delete(db_usuario)
    db.commit()
    return {"message": "Usuario eliminado"}

# --- Endpoints originales como muestra ---
@app.get("/productos")
async def obtener_productos():
    return [
        {"id": 1, "producto": "Disco de Freno Cerámico", "precio": 120.50},
        {"id": 2, "producto": "Bujía", "precio":  45.00},
    ]