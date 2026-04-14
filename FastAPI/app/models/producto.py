"""
Modelo ORM para la tabla de productos (inventario).
"""
from sqlalchemy import Column, Integer, String, DateTime
from app.data.database import Base
from datetime import datetime


class Producto(Base):
    __tablename__ = "productos"

    id = Column(Integer, primary_key=True, index=True)
    nombre = Column(String(100), nullable=False)
    categoria = Column(String(50), nullable=True)
    descripcion = Column(String(255), nullable=True)
    precio = Column(Integer, nullable=False)
    stock_actual = Column(Integer, default=0)
    stock_minimo = Column(Integer, default=5)
    fecha_actualizacion = Column(DateTime, default=datetime.utcnow, onupdate=datetime.utcnow)
