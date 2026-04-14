"""
Servicio de lógica de negocio para Pedidos.
"""
from sqlalchemy.orm import Session
from typing import Optional

from app.models.pedido import Pedido
from app.models.usuario import Usuario
from app.schemas.pedido import PedidoCreate


def get_pedidos(db: Session, cliente: Optional[str] = None, estatus: Optional[str] = None):
    """Obtiene pedidos con filtros opcionales por cliente y estatus."""
    query = db.query(Pedido)

    if cliente:
        # Búsqueda por nombre de cliente a través de la relación
        query = query.join(Usuario).filter(Usuario.nombre.ilike(f"%{cliente}%"))

    if estatus and estatus != "Todos los pedidos":
        query = query.filter(Pedido.estatus == estatus)

    return query.all()


def create_pedido(db: Session, pedido: PedidoCreate):
    """Crea un nuevo pedido."""
    db_pedido = Pedido(**pedido.dict())
    db.add(db_pedido)
    db.commit()
    db.refresh(db_pedido)
    return db_pedido
