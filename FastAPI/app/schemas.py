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

# --- Pedidos ---
class PedidoBase(BaseModel):
    cliente_id: int
    total: int
    estatus: str = "En Proceso"

class PedidoCreate(PedidoBase):
    pass

class PedidoResponse(PedidoBase):
    id: int
    fecha: datetime
    
    class Config:
        from_attributes = True

# Para respuestas anidadas opcionales
class UsuarioConPedidos(UsuarioResponse):
    pedidos: List[PedidoResponse] = []

# --- Productos / Inventario ---
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
