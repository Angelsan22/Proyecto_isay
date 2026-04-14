"""
Servicio de lógica de negocio para Productos (Inventario).
"""
from sqlalchemy.orm import Session
from fastapi import HTTPException
from typing import Optional

from app.models.producto import Producto
from app.schemas.producto import ProductoCreate


def get_productos(db: Session, nombre: Optional[str] = None, categoria: Optional[str] = None):
    """Obtiene productos con filtros opcionales."""
    query = db.query(Producto)
    if nombre:
        query = query.filter(Producto.nombre.ilike(f"%{nombre}%"))
    if categoria and categoria != "Todas las categorías":
        query = query.filter(Producto.categoria == categoria)
    return query.all()


def get_producto_by_id(db: Session, producto_id: int):
    """Obtiene un producto específico por ID."""
    db_producto = db.query(Producto).filter(Producto.id == producto_id).first()
    if not db_producto:
        raise HTTPException(status_code=404, detail="Producto no encontrado")
    return db_producto


def create_producto(db: Session, producto: ProductoCreate):
    """Crea un nuevo producto."""
    db_producto = Producto(**producto.dict())
    db.add(db_producto)
    db.commit()
    db.refresh(db_producto)
    return db_producto


def update_producto(db: Session, producto_id: int, producto: ProductoCreate):
    """Actualiza un producto existente."""
    db_producto = db.query(Producto).filter(Producto.id == producto_id).first()
    if not db_producto:
        raise HTTPException(status_code=404, detail="Producto no encontrado")

    for key, value in producto.dict().items():
        setattr(db_producto, key, value)

    db.commit()
    db.refresh(db_producto)
    return db_producto


def delete_producto(db: Session, producto_id: int):
    """Elimina un producto por ID."""
    db_producto = db.query(Producto).filter(Producto.id == producto_id).first()
    if not db_producto:
        raise HTTPException(status_code=404, detail="Producto no encontrado")
    db.delete(db_producto)
    db.commit()
    return {"message": "Producto eliminado"}
