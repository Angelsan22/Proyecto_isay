from pydantic import BaseModel, EmailStr
from datetime import datetime
from typing import Optional, List

# --- Admins ---
class AdminBase(BaseModel):
    nombre: str
    email: EmailStr

class AdminCreate(AdminBase):
    password: str
    creador_id: Optional[int] = None

class AdminResponse(AdminBase):
    id: int
    fecha_creacion: datetime
    creador_id: Optional[int]
    
    class Config:
        from_attributes = True

class AdminLogin(BaseModel):
    email: EmailStr
    password: str

# --- Usuarios ---
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
