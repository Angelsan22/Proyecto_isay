from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy.orm import Session
from typing import List, Optional

from app import models, schemas, database
from app.database import get_db

router = APIRouter(
    prefix="/productos",
    tags=["Inventario"]
)

@router.get("/", response_model=List[schemas.ProductoResponse])
def read_productos(nombre: Optional[str] = None, categoria: Optional[str] = None, db: Session = Depends(database.get_db)):
    query = db.query(models.Producto)
    if nombre:
        query = query.filter(models.Producto.nombre.ilike(f"%{nombre}%"))
    if categoria and categoria != "Todas las categorías":
        query = query.filter(models.Producto.categoria == categoria)
    return query.all()

@router.get("/{producto_id}", response_model=schemas.ProductoResponse)
def read_producto(producto_id: int, db: Session = Depends(database.get_db)):
    db_producto = db.query(models.Producto).filter(models.Producto.id == producto_id).first()
    if not db_producto:
        raise HTTPException(status_code=404, detail="Producto no encontrado")
    return db_producto

@router.post("/", response_model=schemas.ProductoResponse)
def create_producto(producto: schemas.ProductoCreate, db: Session = Depends(database.get_db)):
    db_producto = models.Producto(**producto.dict())
    db.add(db_producto)
    db.commit()
    db.refresh(db_producto)
    return db_producto

@router.put("/{producto_id}", response_model=schemas.ProductoResponse)
def update_producto(producto_id: int, producto: schemas.ProductoCreate, db: Session = Depends(database.get_db)):
    db_producto = db.query(models.Producto).filter(models.Producto.id == producto_id).first()
    if not db_producto:
        raise HTTPException(status_code=404, detail="Producto no encontrado")
    
    for key, value in producto.dict().items():
        setattr(db_producto, key, value)
    
    db.commit()
    db.refresh(db_producto)
    return db_producto

@router.delete("/{producto_id}")
def delete_producto(producto_id: int, db: Session = Depends(database.get_db)):
    db_producto = db.query(models.Producto).filter(models.Producto.id == producto_id).first()
    if not db_producto:
        raise HTTPException(status_code=404, detail="Producto no encontrado")
    db.delete(db_producto)
    db.commit()
    return {"message": "Producto eliminado"}
