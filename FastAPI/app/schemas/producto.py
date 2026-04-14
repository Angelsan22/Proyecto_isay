"""
Schemas Pydantic para validación de Productos (Inventario).
"""
from pydantic import BaseModel
from datetime import datetime
from typing import Optional


class ProductoBase(BaseModel):
    nombre: str
    categoria: Optional[str] = "Autopartes"
    descripcion: Optional[str] = None
    precio: int
    stock_actual: int
    stock_minimo: int = 5


class ProductoCreate(ProductoBase):
    pass


class ProductoResponse(ProductoBase):
    id: int
    fecha_actualizacion: datetime

    class Config:
        from_attributes = True
