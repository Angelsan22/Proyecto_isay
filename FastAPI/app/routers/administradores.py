from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy.orm import Session
from typing import List

from app import models, schemas, database
from app.database import get_db
from app.security.helpers import get_password_hash, verify_password

router = APIRouter(
    prefix="/admins",
    tags=["Administradores"]
)

@router.get("/", response_model=List[schemas.AdminResponse])
def read_admins(skip: int = 0, limit: int = 100, db: Session = Depends(database.get_db)):
    admins = db.query(models.Admin).offset(skip).limit(limit).all()
    return admins

@router.post("/", response_model=schemas.AdminResponse)
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

@router.post("/login", tags=["Autentificación"])
def login_admin(admin: schemas.AdminLogin, db: Session = Depends(database.get_db)):
    db_admin = db.query(models.Admin).filter(models.Admin.email == admin.email).first()
    if not db_admin or not verify_password(admin.password, db_admin.password_hash):
        raise HTTPException(status_code=401, detail="Credenciales incorrectas")
    
    return {"message": "Login exitoso", "admin_id": db_admin.id, "nombre": db_admin.nombre, "email": db_admin.email}
 
@router.put("/{admin_id}", response_model=schemas.AdminResponse)
def update_admin(admin_id: int, admin_data: schemas.AdminCreate, db: Session = Depends(database.get_db)):
    db_admin = db.query(models.Admin).filter(models.Admin.id == admin_id).first()
    if not db_admin:
        raise HTTPException(status_code=404, detail="Administrador no encontrado")
    if admin_data.email != db_admin.email:
        exist = db.query(models.Admin).filter(models.Admin.email == admin_data.email).first()
        if exist:
            raise HTTPException(status_code=400, detail="Email ya registrado por otro admin")
    
    db_admin.nombre = admin_data.nombre
    db_admin.email = admin_data.email
    db_admin.password_hash = get_password_hash(admin_data.password)
    
    db.commit()
    db.refresh(db_admin)
    return db_admin

@router.delete("/{admin_id}")
def delete_admin(admin_id: int, db: Session = Depends(database.get_db)):
    db_admin = db.query(models.Admin).filter(models.Admin.id == admin_id).first()
    if not db_admin:
        raise HTTPException(status_code=404, detail="Administrador no encontrado")
    db.delete(db_admin)
    db.commit()
    return {"message": "Admin eliminado"}
