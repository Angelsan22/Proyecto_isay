"""
Modelo ORM para la tabla de administradores.
"""
from sqlalchemy import Column, Integer, String, DateTime, ForeignKey
from sqlalchemy.orm import relationship
from app.data.database import Base
from datetime import datetime


class Admin(Base):
    __tablename__ = "admins"

    id = Column(Integer, primary_key=True, index=True)
    nombre = Column(String(100), nullable=False)
    email = Column(String(120), unique=True, index=True, nullable=False)
    password_hash = Column(String(256), nullable=False)
    fecha_creacion = Column(DateTime, default=datetime.utcnow)
    creador_id = Column(Integer, ForeignKey('admins.id'), nullable=True)

    creador = relationship('Admin', remote_side=[id], backref='admins_creados')
