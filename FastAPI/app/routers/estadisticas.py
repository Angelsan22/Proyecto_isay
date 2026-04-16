from fastapi import APIRouter, Depends
from sqlalchemy.orm import Session

from app import models, database
from app.database import get_db

router = APIRouter(
    tags=["Inventario"]
)

@router.get("/categorias/")
def read_categorias(db: Session = Depends(database.get_db)):
    categorias = db.query(models.Producto.categoria).distinct().all()
    return [{"id": c[0].lower(), "nombre": c[0]} for c in categorias if c[0]]

@router.get("/marcas/")
def read_marcas(db: Session = Depends(database.get_db)):
    productos = db.query(models.Producto.nombre).all()
    marcas = set()
    for p in productos:
        nombre = p[0]
        if "(" in nombre and ")" in nombre:
            marca = nombre[nombre.find("(")+1:nombre.find(")")]
            marcas.add(marca)
    
    return [{"id": m.lower(), "nombre": m} for m in sorted(list(marcas))]

@router.get("/dashboard/estadisticas")
def get_dashboard_stats(db: Session = Depends(database.get_db)):
    pedidos = db.query(models.Pedido).all()
    pendientes = sum(1 for p in pedidos if p.estatus == "En Proceso")
    enviados = sum(1 for p in pedidos if p.estatus == "Enviado")
    entregados = sum(1 for p in pedidos if p.estatus == "Entregado")
    cancelados = sum(1 for p in pedidos if p.estatus == "Cancelado")
    productos = db.query(models.Producto).all()
    stock_bajo = [
        {"id": p.id, "nombre": p.nombre, "stock_actual": p.stock_actual, "stock_minimo": p.stock_minimo} 
        for p in productos if p.stock_actual <= p.stock_minimo
    ]
    
    return {
        "pedidos": {
            "total": len(pedidos),
            "pendientes": pendientes,
            "enviados": enviados,
            "entregados": entregados,
            "cancelados": cancelados
        },
        "inventario": {
            "total_productos": len(productos),
            "alertas_stock_bajo": len(stock_bajo),
            "productos_agotados": sum(1 for p in productos if p.stock_actual <= 0),
            "detalle_bajos": stock_bajo
        }
    }
