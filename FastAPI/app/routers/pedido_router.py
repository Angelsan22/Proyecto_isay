"""
Router de endpoints para Pedidos.
"""
from fastapi import APIRouter, Depends
from sqlalchemy.orm import Session
from typing import List, Optional

from app.data.database import get_db
from app.schemas.pedido import PedidoCreate, PedidoResponse
from app.services import pedido_service

router = APIRouter(prefix="/pedidos", tags=["Gestión de Pedidos"])


@router.get("/", response_model=List[PedidoResponse])
def read_pedidos(cliente: Optional[str] = None, estatus: Optional[str] = None, db: Session = Depends(get_db)):
    return pedido_service.get_pedidos(db, cliente, estatus)


@router.post("/", response_model=PedidoResponse)
def create_pedido(pedido: PedidoCreate, db: Session = Depends(get_db)):
    return pedido_service.create_pedido(db, pedido)
