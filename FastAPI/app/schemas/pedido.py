"""
Schemas Pydantic para validación de Pedidos.
"""
from pydantic import BaseModel
from datetime import datetime


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
