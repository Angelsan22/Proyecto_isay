"""
Router de endpoints para Administradores.
"""
from fastapi import APIRouter, Depends
from sqlalchemy.orm import Session
from typing import List

from app.data.database import get_db
from app.schemas.admin import AdminCreate, AdminResponse, AdminLogin
from app.services import admin_service

router = APIRouter(prefix="/admins", tags=["Administradores"])


@router.get("/", response_model=List[AdminResponse])
def read_admins(skip: int = 0, limit: int = 100, db: Session = Depends(get_db)):
    return admin_service.get_admins(db, skip, limit)


@router.post("/", response_model=AdminResponse)
def create_admin(admin: AdminCreate, db: Session = Depends(get_db)):
    return admin_service.create_admin(db, admin)


@router.post("/login", tags=["Autenticación"])
def login_admin(admin: AdminLogin, db: Session = Depends(get_db)):
    return admin_service.authenticate_admin(db, admin.email, admin.password)


@router.delete("/{admin_id}")
def delete_admin(admin_id: int, db: Session = Depends(get_db)):
    return admin_service.delete_admin(db, admin_id)
