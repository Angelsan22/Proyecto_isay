"""
Punto de entrada de la aplicación FastAPI.
Registra todos los routers modulares y crea las tablas de la BD.
"""
from fastapi import FastAPI

from app.core.config import settings
from app.data.database import engine

# Importar todos los modelos para que SQLAlchemy los registre
from app.models.admin import Admin
from app.models.usuario import Usuario
from app.models.pedido import Pedido
from app.models.producto import Producto
from app.data.database import Base

# Importar routers
from app.routers import admin_router, usuario_router, pedido_router, producto_router

# Crear tablas
Base.metadata.create_all(bind=engine)

app = FastAPI(
    title=settings.APP_TITLE,
    description=settings.APP_DESCRIPTION,
    version=settings.APP_VERSION
)

# Registrar routers
app.include_router(admin_router.router)
app.include_router(usuario_router.router)
app.include_router(pedido_router.router)
app.include_router(producto_router.router)


# --- Endpoint original legacy (mantenido para compatibilidad) ---
@app.get("/productos_legacy", tags=["Catálogo de Productos"])
async def obtener_productos_legacy():
    pass