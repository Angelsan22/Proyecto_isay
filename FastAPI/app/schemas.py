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

# --- Productos / Inventario ---
class ProductoBase(BaseModel):
    nombre: str
    categoria: Optional[str] = "Autopartes"
    descripcion: Optional[str] = None
    precio: int
    stock_actual: int
    stock_minimo: int = 5
    imagen: Optional[str] = None

class ProductoCreate(ProductoBase):
    pass

class ProductoResponse(ProductoBase):
    id: int
    fecha_actualizacion: datetime
    
    class Config:
        from_attributes = True

# --- Usuarios ---
class UsuarioBase(BaseModel):
    nombre: str
    correo: EmailStr

class UsuarioCreate(UsuarioBase):
    password: str

class UsuarioLogin(BaseModel):
    correo: EmailStr
    password: str

class UsuarioPasswordUpdate(BaseModel):
    current_password: str
    new_password: str

class UsuarioResponse(UsuarioBase):
    id: int
    fecha_registro: Optional[datetime] = None
    
    class Config:
        from_attributes = True

# --- Detalles de Pedido ---
class DetallePedidoBase(BaseModel):
    producto_id: int
    cantidad: int
    precio_unitario: int

class DetallePedidoCreate(DetallePedidoBase):
    pass

class DetallePedidoResponse(DetallePedidoBase):
    id: int
    pedido_id: int
    producto: Optional[ProductoResponse] = None
    
    class Config:
        from_attributes = True

# --- Pedidos ---
class PedidoBase(BaseModel):
    cliente_id: int
    total: int
    estatus: str = "En Proceso"
    direccion_envio: Optional[str] = None
    ciudad: Optional[str] = None
    codigo_postal: Optional[str] = None
    telefono: Optional[str] = None
    metodo_pago: Optional[str] = "Tarjeta"

class PedidoCreate(PedidoBase):
    items: List[DetallePedidoCreate] = []

class PedidoResponse(PedidoBase):
    id: int
    fecha: datetime
    cliente: Optional[UsuarioResponse] = None
    detalles: List[DetallePedidoResponse] = []
    
    class Config:
        from_attributes = True

# Para respuestas anidadas opcionales
class UsuarioConPedidos(UsuarioResponse):
    pedidos: List[PedidoResponse] = []
