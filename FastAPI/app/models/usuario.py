from sqlalchemy import Column, Integer, String, DateTime
from sqlalchemy.orm import relationship
from app.data.database import Base
from datetime import datetime

class Usuario(Base):
    __tablename__ = "usuarios"

    id = Column(Integer, primary_key=True, index=True)
    nombre = Column(String(100), nullable=False)
    correo = Column(String(120), unique=True, index=True, nullable=False)
    password_hash = Column(String(256), nullable=True)  # Gestionado por FastAPI
    fecha_registro = Column(DateTime, default=datetime.utcnow)

    pedidos = relationship("Pedido", back_populates="cliente")
