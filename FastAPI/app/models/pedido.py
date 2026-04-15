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
    estatus = Column(String(50), default="En Proceso")  # En Proceso, Enviado, Entregado, Cancelado

    # Logistic fields
    direccion_envio = Column(String(255), nullable=True)
    ciudad = Column(String(100), nullable=True)
    codigo_postal = Column(String(10), nullable=True)
    telefono = Column(String(20), nullable=True)
    metodo_pago = Column(String(50), nullable=True)

    cliente = relationship("Usuario", back_populates="pedidos")
    detalles = relationship("DetallePedido", back_populates="pedido", cascade="all, delete-orphan")


class DetallePedido(Base):
    __tablename__ = "detalle_pedidos"

    id = Column(Integer, primary_key=True, index=True)
    pedido_id = Column(Integer, ForeignKey("pedidos.id"), nullable=False)
    producto_id = Column(Integer, ForeignKey("productos.id"), nullable=False)
    cantidad = Column(Integer, nullable=False)
    precio_unitario = Column(Integer, nullable=False)

    pedido = relationship("Pedido", back_populates="detalles")
    producto = relationship("Producto")
