from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy.orm import Session, joinedload
from typing import List, Optional

from app import models, schemas
from app.data import database
from app.data.database import get_db

router = APIRouter(
    prefix="/pedidos",
    tags=["Gestión de Pedidos"]
)

@router.get("/", response_model=List[schemas.PedidoResponse])
def read_pedidos(cliente: Optional[str] = None, cliente_id: Optional[int] = None, estatus: Optional[str] = None, db: Session = Depends(database.get_db)):
    query = db.query(models.Pedido).options(joinedload(models.Pedido.cliente))
    
    if cliente_id:
        query = query.filter(models.Pedido.cliente_id == cliente_id)
        
    if cliente:
        # Búsqueda por nombre de cliente a través de la relación
        query = query.join(models.Usuario).filter(models.Usuario.nombre.ilike(f"%{cliente}%"))
    
    if estatus and estatus != "Todos los pedidos":
        query = query.filter(models.Pedido.estatus == estatus)
        
    return query.all()

@router.get("/{pedido_id}", response_model=schemas.PedidoResponse)
def read_pedido(pedido_id: int, db: Session = Depends(database.get_db)):
    db_pedido = db.query(models.Pedido).options(joinedload(models.Pedido.cliente)).filter(models.Pedido.id == pedido_id).first()
    if not db_pedido:
        raise HTTPException(status_code=404, detail="Pedido no encontrado")
    return db_pedido

@router.post("/", response_model=schemas.PedidoResponse)
def create_pedido(pedido: schemas.PedidoCreate, db: Session = Depends(database.get_db)):
    # 1. Crear la cabecera del pedido (sin los items)
    pedido_data = pedido.dict(exclude={'items'})
    db_pedido = models.Pedido(**pedido_data)
    db.add(db_pedido)
    db.flush() # Para obtener el ID del pedido autogenerado

    # 2. Procesar cada item: Guardar detalle y Descontar Stock
    for item in pedido.items:
        # Registrar el detalle
        db_detalle = models.DetallePedido(
            pedido_id=db_pedido.id,
            producto_id=item.producto_id,
            cantidad=item.cantidad,
            precio_unitario=item.precio_unitario
        )
        db.add(db_detalle)

        # Buscar el producto y descontar stock
        db_producto = db.query(models.Producto).filter(models.Producto.id == item.producto_id).first()
        if db_producto:
            # Descontamos la cantidad comprada del stock actual
            if db_producto.stock_actual >= item.cantidad:
                db_producto.stock_actual -= item.cantidad
            else:
                # Si no hay suficiente stock, lo dejamos en 0
                db_producto.stock_actual = 0
            
    db.commit()
    db.refresh(db_pedido)
    return db_pedido

@router.patch("/{pedido_id}/estatus")
def update_estatus_pedido(pedido_id: int, body: dict, db: Session = Depends(database.get_db)):
    db_pedido = db.query(models.Pedido).filter(models.Pedido.id == pedido_id).first()
    if not db_pedido:
        raise HTTPException(status_code=404, detail="Pedido no encontrado")
    
    nuevo_estatus = body.get("estatus")
    if nuevo_estatus not in ["En Proceso", "Enviado", "Entregado", "Cancelado"]:
        raise HTTPException(status_code=400, detail="Estatus no válido")
    
    db_pedido.estatus = nuevo_estatus
    db.commit()
    db.refresh(db_pedido)
    return {"message": "Estatus actualizado", "pedido_id": pedido_id, "estatus": nuevo_estatus}
