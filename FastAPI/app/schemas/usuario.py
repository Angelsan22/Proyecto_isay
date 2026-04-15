"""
Schemas Pydantic para validación de Usuarios (Clientes).
"""
from pydantic import BaseModel, EmailStr
from datetime import datetime
from typing import List


class UsuarioBase(BaseModel):
    nombre: str
    correo: EmailStr


class UsuarioCreate(UsuarioBase):
    pass


class UsuarioResponse(UsuarioBase):
    id: int
    fecha_registro: datetime

    class Config:
        from_attributes = True
