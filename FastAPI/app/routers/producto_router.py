"""
Router de endpoints para Productos (Inventario).
"""
from fastapi import APIRouter, Depends
from sqlalchemy.orm import Session
from typing import List, Optional

from app.data.database import get_db
from app.schemas.producto import ProductoCreate, ProductoResponse
from app.services import producto_service

router = APIRouter(prefix="/productos", tags=["Gestión de Inventario"])


@router.get("/", response_model=List[ProductoResponse])
def read_productos(nombre: Optional[str] = None, categoria: Optional[str] = None, db: Session = Depends(get_db)):
    return producto_service.get_productos(db, nombre, categoria)


@router.get("/{producto_id}", response_model=ProductoResponse)
def read_producto(producto_id: int, db: Session = Depends(get_db)):
    return producto_service.get_producto_by_id(db, producto_id)


@router.post("/", response_model=ProductoResponse)
def create_producto(producto: ProductoCreate, db: Session = Depends(get_db)):
    return producto_service.create_producto(db, producto)


@router.put("/{producto_id}", response_model=ProductoResponse)
def update_producto(producto_id: int, producto: ProductoCreate, db: Session = Depends(get_db)):
    return producto_service.update_producto(db, producto_id, producto)


@router.delete("/{producto_id}")
def delete_producto(producto_id: int, db: Session = Depends(get_db)):
    return producto_service.delete_producto(db, producto_id)
