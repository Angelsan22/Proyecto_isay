"""
Modelo ORM para la tabla de pedidos.
"""
from sqlalchemy import Column, Integer, String, DateTime, ForeignKey
from sqlalchemy.orm import relationship
from app.data.database import Base
from datetime import datetime


class Pedido(Base):
    __tablename__ = "pedidos"

    id = Column(Integer, primary_key=True, index=True)
    cliente_id = Column(Integer, ForeignKey("usuarios.id"), nullable=False)
    fecha = Column(DateTime, default=datetime.utcnow)
    total = Column(Integer, nullable=False)
    estatus = Column(String(50), default="En Proceso")  # En Proceso, Enviado, Entregado

    cliente = relationship("Usuario", back_populates="pedidos")
